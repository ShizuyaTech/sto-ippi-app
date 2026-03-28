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
        if (!Schema::hasColumn('stock_taking_details', 'tag_number')) {
            Schema::table('stock_taking_details', function (Blueprint $table) {
                $table->string('tag_number')->after('stock_taking_session_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_details', function (Blueprint $table) {
            $table->dropColumn('tag_number');
        });
    }
};
