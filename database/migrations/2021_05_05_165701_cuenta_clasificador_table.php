<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CuentaClasificadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuenta_clasificador', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->text('descripcion')->nullable();                        
            $table->integer('numero_clasificador');                        
            $table->integer('rubro_id');                        
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
        Schema::dropIfExists('cuenta_clasificador');
    }
}
