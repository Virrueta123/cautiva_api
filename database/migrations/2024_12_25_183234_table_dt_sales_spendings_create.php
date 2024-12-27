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
        Schema::create('dt_sales_spendings', function (Blueprint $table) {
            $table->id('dt_id'); // Clave primaria auto incremental
            $table->unsignedBigInteger('payment_id')->nullable(); // Relación con payments
            $table->unsignedBigInteger('spending_id')->nullable(); // Relación con spendings
            $table->timestamps(); // Campos created_at y updated_at
            $table->softDeletes(); // Campo deleted_at

            // Llave foránea para payments
            $table->foreign('payment_id')
                ->references('payment_id')
                ->on('payments')
                  ->onDelete('cascade');

            // Llave foránea para spendings
            $table->foreign('spending_id')
                ->references('spending_id') // Asegúrate que el nombre sea correcto
                ->on('spendings')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
