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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->unsignedInteger('category_id')->nullable(false);
            $table->unsignedInteger('model_id')->nullable(false);
            $table->string('product_name')->unique();
            $table->decimal('product_purchase', 10, 2)->unsigned();
            $table->decimal('product_sales', 10, 2)->unsigned();
            $table->decimal('product_profit', 10, 2)->unsigned();
            $table->integer('product_stock')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->bigInteger('barcode')->unique()->nullable();

            // Claves foráneas
            $table->foreign('category_id')
                ->references('category_id')
                ->on('categories');

            $table->foreign('model_id')
                ->references('model_id')
                ->on('models');

            $table->foreign('created_by')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
