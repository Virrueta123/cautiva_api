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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('client_id'); // bigint UNSIGNED AUTO_INCREMENT
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->string('dni')->nullable();
            $table->string('address')->nullable();
            $table->integer('phone')->nullable(); 
            $table->string('ruc')->nullable();
            $table->string('bussiness_name')->nullable();
            $table->char('is_default', 1)->default('N');
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
            $table->unsignedBigInteger('created_by')->nullable();

            // Clave foránea para created_by referenciada a la tabla users
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Borra el cliente si el usuario asociado se elimina
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
