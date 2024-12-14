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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id'); // bigint UNSIGNED AUTO_INCREMENT
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('box_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            // Campos adicionales
            $table->char('tipo_documento', 1)->nullable();
            $table->string('serie', 255)->nullable();
            $table->string('correlativo', 255)->nullable();
            $table->integer('setRuc')->nullable();
            $table->string('setRazonSocial', 255)->nullable(); 
            $table->string('setDireccion', 255)->nullable();
            $table->string('setCodLocal', 255)->nullable();

            // Campos de monto
            $table->decimal('setMtoOperGravadas', 10, 2)->nullable();
            $table->decimal('setMtoIGV', 10, 2)->nullable();
            $table->decimal('setTotalImpuestos', 10, 2)->nullable();
            $table->decimal('setValorVenta', 10, 2)->nullable();
            $table->decimal('setSubTotal', 10, 2)->nullable();
            $table->decimal('setMtoImpVenta', 10, 2)->nullable();
            $table->decimal('setMtoOperExoneradas', 10, 2)->nullable();
            $table->decimal('setMtoOperInafectas', 10, 2)->nullable();
            $table->decimal('setMtoOtrosCargos', 10, 2)->nullable();

            // Timestamps y otros campos
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->char('estado', 1)->nullable();
            $table->timestamp('fecha_baja')->nullable();
            $table->integer('setDni')->nullable();
            $table->string('setNombre', 255)->nullable();
            $table->string('setApellido', 255)->nullable();
            $table->decimal('descuento', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->string('observations', 500)->nullable();
            $table->string('message_error', 500)->nullable();
            $table->string('codigo_error', 200)->nullable();
            

            // Claves foráneas
            $table->foreign('client_id')->references('client_id')->on('clients')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('box_id')->references('box_id')->on('boxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
