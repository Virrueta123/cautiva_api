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
        Schema::create('spendings', function (Blueprint $table) {
            $table->id('spending_id')->primary();
            $table->foreignId('spending_type_id')->nullable()->constrained('spending_type', 'spending_type_id')->onDelete('set null');
            $table->string('description', 255)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Agrega el campo `deleted_at`
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
