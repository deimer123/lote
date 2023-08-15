<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id');   
                      
            $table->foreignId('user_id')->constrained; 
            $table->foreignId('lote_id')->constrained;
            $table->string('cuotas');
            $table->string('certificate_image');
            $table->string('original_filename');
            $table->string('valor_cuota');
            $table->string('valor_pagado');
            $table->string('valor_deuda');
            $table->string('agente_id');             
                    
            $table->timestamps();    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
