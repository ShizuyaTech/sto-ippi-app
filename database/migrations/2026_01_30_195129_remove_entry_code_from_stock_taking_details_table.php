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
        Schema::table('stock_taking_details', function (Blueprint $table) {
            $table->dropColumn('entry_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_details', function (Blueprint $table) {
            $table->string('entry_code')->after('stock_taking_session_id');
        });
    }
};
