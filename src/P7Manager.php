<?php 
namespace Valepuri\P7Manager;

use DB;
use Config;
use Zipper;
use Storage;

class P7Manager{

	public function getFile($chiavi){

		$table = DB::table('PDFEstratti');
		foreach ($chiavi as $key => $value) {
			$table -> where($key, $value);
		}
		$result = $table -> get();

		$arrRet = [];

		if(count($result) > 0){
			foreach ($result as $key => $value) {
				$arrRet[$key]['PDF'] = $value -> PDF_Folder.$value-> PDF;
				$arrRet[$key]['P7M'] = $value -> P7M_Folder.$value-> P7M;
			}
			return $arrRet;
		}else{

			return FALSE;

		}

	}

	public function extractOrVerify($pathBase, $pathDestinazione, $file, $save=1){

		//SE IL SAVE è a 0, fa solo la verifica, altrimenti pure l'estrazione

		$filePath = $pathBase.'/'.$file;
		$nomeSenzaEstensione = explode('.', $file);

		$nomePDF = 'ESTRATTO_';

		if(isset($nomeSenzaEstensione[0])){
			$nomePDF .= $nomeSenzaEstensione[0].'.pdf';
		}
		
		$destinationPath = $pathDestinazione.'/'.$nomePDF;
		$command = 'openssl smime -verify -noverify -in '.base_path().$filePath.' -inform der -out '.
		base_path().$destinationPath;
		//return $command;

		exec($command, $OUTPUTARRAY, $STRINGOUTPUT);

		$DISCO = Config::get('p7manager.FILESYSTEM_DISK');

		if(Storage::disk($DISCO)->exists($destinationPath)){
			if($save < 1){
				exec('rm '.$destinationPath);
				return TRUE;
			}else{
				return $nomePDF;
			}
		}else{
			return FALSE;
		}
	}


	public function extractAndSaveToDB($pathBase, $pathDestinazione, $chiaviQuery = [], $valoriStaticiDaInserire = [], $file){

		$extract = $this -> extractOrVerify($pathBase, $pathDestinazione, $file);
		$FILEP7M =  $file;
		$FILEPDF = NULL;

		//return $extract;
		if($extract){
			
			$FILEPDF = $extract;
			//aggiungo ai campi statici quelli dinamici dei file
			$ColonnaFileP7M =  Config::get('p7manager.COLONNA_FILE_DA_ESTRARRE');
			$ColonnaFilePDF =  Config::get('p7manager.COLONNA_FILE_ESTRATTO');
			$valoriStaticiDaInserire[$ColonnaFileP7M] = $FILEP7M;
			$valoriStaticiDaInserire[$ColonnaFilePDF] = $FILEPDF;

			$up = PDFEstratti::updateOrCreate($chiaviQuery, $valoriStaticiDaInserire);
			return TRUE;
		}else{
			return FALSE;
		}
		
	}

	public function extractInZipFromDB($nomeZip, $chiavi = [], $folderNameAsColonnaDb=NULL){
		//$chiavi = ["COLONNA" => 'VALORE'];
		$table = DB::table('PDFEstratti');
		foreach ($chiavi as $key => $value) {
			$table -> where($key, $value);
		}
		$result = $table -> get();

		if(count($result) > 0){

			$DISCO = Config::get('p7manager.FILESYSTEM_DISK');
			$ColonnaFolderFileP7M =  Config::get('p7manager.COLONNA_FOLDER_FILE_DA_ESTRARRE');
			$ColonnaFolderFilePDF =  Config::get('p7manager.COLONNA_FOLDER_FILE_ESTRATTO');
			$ColonnaFileP7M =  Config::get('p7manager.COLONNA_FILE_DA_ESTRARRE');
			$ColonnaFilePDF =  Config::get('p7manager.COLONNA_FILE_ESTRATTO');

			$countName = 0;
			$newName = str_slug($nomeZip);
			//verifica che non esiste la cartella, in caso la crea con il numero progressivo
			while(Storage::disk($DISCO)->exists('ZIP/'.$newName)){
				$newName = str_slug($nomeZip).'_'.$countName;
				$countName++;
			}
			
			//crea la cartella
			Storage::disk($DISCO)->makeDirectory('ZIP/'.$newName);

			foreach ($result as $kr => $vr) {
				$directoryAttuale = NULL;
				if($folderNameAsColonnaDb != NULL){
					$directoryAttuale = 'ZIP/'.$newName.'/'.$vr->{$folderNameAsColonnaDb};
				}else{
					$directoryAttuale = 'ZIP/'.$newName;
				}
				
				if(!Storage::disk($DISCO)->exists($directoryAttuale)){
					Storage::disk($DISCO)->makeDirectory($directoryAttuale);
				}	

				$pathP7M = $vr->{$ColonnaFolderFileP7M}.$vr->{$ColonnaFileP7M};
				$pathPDF = $vr->{$ColonnaFolderFilePDF}.$vr->{$ColonnaFilePDF};

				if (Storage::disk($DISCO)->exists($directoryAttuale.'/'.$vr->{$ColonnaFileP7M})) {
					Storage::disk($DISCO)->delete($directoryAttuale.'/'.$vr->{$ColonnaFileP7M});
				}
				if (Storage::disk($DISCO)->exists($directoryAttuale.'/'.$vr->{$ColonnaFilePDF})) {
					Storage::disk($DISCO)->delete($directoryAttuale.'/'.$vr->{$ColonnaFilePDF});
				}

				Storage::disk($DISCO)->copy($pathP7M, $directoryAttuale.'/'.$vr->{$ColonnaFileP7M});
				Storage::disk($DISCO)->copy($pathPDF, $directoryAttuale.'/'.$vr->{$ColonnaFilePDF});		
			}

			$files = glob('ZIP/'.$newName.'/');
			Zipper::make('ZIP/'.$newName.'.zip')->add($files);
			$nomeComleto = $newName.'.zip';

			//Storage::disk($DISCO)->deleteDirectory('ZIP/'.$newName);

			return [
				"nome" => $newName.'.zip',
				"path" => '/ZIP/'.$nomeComleto
			];
		}else{

			return FALSE;

		}

	}

}