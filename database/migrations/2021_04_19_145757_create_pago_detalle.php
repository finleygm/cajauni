<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_detalle', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->integer('posicion')->nullable();            
            $table->integer('cantidad')->nullable();;        
            $table->integer('precio_unitario'); 
            $table->integer('cuenta_id'); 
            $table->double('monto',8,2);
            $table->text('descripcion')->nullable();
            $table->integer('pago_id');
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
        Schema::dropIfExists('pago_detalle');
    }
}
