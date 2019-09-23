<?php

namespace Valepuri\P7Manager;

use Illuminate\Database\Eloquent\Model;
use Config;

class PDFEstratti extends Model
{
    protected $table = 'PDFEstratti';
    protected $fillable = array();
    protected $guarded = array();
    protected $dateFormat = 'Y-m-d H:i';

    public function __construct(){
    	$allCampi = Config::get('p7manager.COLONNEDB');
    	/*foreach ($allCampi as $key => $value) {
    		array_push($this -> fillable, $key);
    	}*/
    }
}
