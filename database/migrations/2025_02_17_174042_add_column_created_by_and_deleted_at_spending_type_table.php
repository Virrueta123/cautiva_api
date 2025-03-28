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
        Schema::table('spending_type', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->after('created_at');
            $table->integer('created_by')->nullable()->after('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spending_type', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('created_by');
        });
    }
};
