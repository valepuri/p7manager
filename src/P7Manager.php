<?php 

use DB;

class P7Manager{

	public $FOLDER = NULL;
	public $DESTINATIONFOLDER = NULL;

	public function __construct($pathBase, $pathDestinazione){
		$this -> FOLDER = base_path().$pathBase;
		$this -> DESTINATIONFOLDER = base_path().$pathDestinazione;
	}

	public function extract($file, $save=1){

		$filePath = $this -> FOLDER.'/'.$file;
		$nomeSenzaEstensione = explode('.', $file);

		$nomePDF = 'ESTRATTO_';

		if(isset($nomeSenzaEstensione[0])){
			$nomePDF .= $nomeSenzaEstensione[0].'.pdf';
		}
		
		$destinationPath = $this -> DESTINATIONFOLDER.'/'.$nomePDF;
		$command = 'openssl smime -verify -in '.$filePath.' -inform der -out '.
		$destinationPath;

		exec($command, $OUTPUTARRAY, $STRINGOUTPUT);

		if($STRINGOUTPUT > 2){
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

	public function extractInZipFromDB($dbTable, $chiavi = [], $colonna, $folderName=NULL){
		//$chiavi = ["COLONNA" => 'VALORE'];

		$table = DB::table($dbTable);
		foreach ($chiavi as $key => $value) {
			$table -> where($key, $value);
		}
		$result = $table -> get();

	}

}