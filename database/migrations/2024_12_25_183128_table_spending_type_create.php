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
       Schema::create('spending_type', function (Blueprint $table) {
            $table->id('spending_type_id')->nullable(false); // Equivalente a UNSIGNED AUTO_INCREMENT
            $table->string('spending_type_name', 255)->nullable();
            $table->timestamps(); // Incluye `created_at` y `updated_at`
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
