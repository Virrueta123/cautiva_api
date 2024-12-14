<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->bigIncrements('box_id'); // Clave primaria auto-incremental
            $table->string('reference')->nullable(); // Columna de referencia
            $table->timestamp('closing_date')->nullable(); // Fecha de cierre
            $table->timestamp('opening_date')->nullable(); // Fecha de apertura
            $table->char('status', 1)->nullable(); // Estado (char de 1)
            $table->decimal('initial_balance', 10, 2)->nullable(); // Balance inicial
            $table->decimal('final_balance', 10, 2)->nullable(); // Balance final
            $table->unsignedBigInteger('created_by')->nullable(); // Relación con usuarios
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // deleted_at

            // Clave foránea
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Elimina en cascada si el usuario es eliminado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes');
    }
};
