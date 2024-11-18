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
        Schema::create('categories', function (Blueprint $table) {
            // Cambiamos a NOT NULL explícitamente
            $table->increments('category_id')->nullable(false);
            $table->string('category_name', 200)->nullable();
            $table->string('category_description', 300)->nullable();
            $table->timestamps(); // Esto crea created_at y updated_at
            $table->softDeletes(); // Esto crea deleted_at
            $table->unsignedBigInteger('created_by')->nullable();
            
            // Clave foránea
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
