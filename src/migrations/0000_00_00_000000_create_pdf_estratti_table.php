<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdfEstrattiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PDFEstratti', function (Blueprint $table) {
            $COLONNE = Config::get('p7manager.COLONNEDB');
            $table->increments('id');
            foreach ($COLONNE as $nomecol => $tipocol) {
                switch($tipocol){
                    case 'string': $table->string($nomecol);break;
                    case 'integer': $table->integer($nomecol);break;
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
        Schema::dropIfExists('PDFEstratti');
    }
}