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
        Schema::create('sizes', function (Blueprint $table) {
            $table->increments('size_id');
            $table->string('size_name', 200)->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); 
            $table->timestamps();
            $table->softDeletes();


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
        Schema::dropIfExists('sizes');
    }
};
