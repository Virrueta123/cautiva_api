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
        Schema::create('dt_sales', function (Blueprint $table) {
            $table->id('dt_sale_id'); // bigint UNSIGNED AUTO_INCREMENT
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('Cantidad', 10, 2)->nullable();
            $table->integer('PorcentajeIgv')->nullable();
            $table->integer('Igv')->nullable();
            $table->decimal('TotalImpuestos', 10, 2)->nullable();
            $table->decimal('MtoValorVenta', 10, 2)->nullable();
            $table->decimal('MtoValorUnitario', 10, 2)->nullable();
            $table->decimal('MtoPrecioUnitario', 10, 2)->nullable();
            $table->string('CodProducto',30)->nullable();
            $table->string('Unidad', 45)->nullable();
            $table->string('Descripcion', 255)->nullable();
            $table->integer('TipAfeIgv')->nullable();
            $table->decimal('BaseIgv', 10, 2)->nullable();
            $table->decimal('discount', 3, 2)->nullable();
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at

            // Claves foráneas
            $table->foreign('sale_id')
                ->references('sale_id')
                ->on('sales')
                ->onDelete('cascade'); // Ajustar según necesidad

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade'); // Ajustar según necesidad

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Ajustar según necesidad
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dt_sales');
    }
};
