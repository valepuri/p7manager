<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Config;

class CreatePdfEstrattiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $COLONNE = Config::get('p7manager.COLONNEDB');
        Schema::create('PDFEstratti', function (Blueprint $table) {
            $table->increments('id');
            foreach ($COLONNE as $key => $value) {
                switch($key){
                    case 'string': $table->string($value); break;
                    case 'integer': $table->integer($value); break;
                }
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}