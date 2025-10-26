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
        Schema::create('config', function (Blueprint $table) {
            $table->id("config_id");
            $table->string('clave', 100)->unique();
            //enum('numero','texto','precio') solo estos valores
            $table->enum('tipo', ['numero', 'texto', 'precio']);
            $table->string('valor');
            $table->string('label')->nullable();
            $table->decimal('precio', 8, 2)->nullable();
            $table->decimal('cantidad', 8, 2)->nullable();
            $table->timestamps(); // creado_en y actualizado_en
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
