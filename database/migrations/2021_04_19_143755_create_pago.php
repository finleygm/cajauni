<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->integer('serie');
            $table->integer('user_id');
            $table->integer('cliente_id');        
            $table->date('fecha_pago')->nullable();                  
            $table->string('lugar')->nullable();      
            $table->integer('cuenta_clasificador_id');
            $table->double('total',8,2);      
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
        Schema::dropIfExists('pago');
    }
}
