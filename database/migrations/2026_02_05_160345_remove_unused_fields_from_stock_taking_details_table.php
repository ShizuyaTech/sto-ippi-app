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
        if (Schema::hasColumn('stock_taking_details', 'system_quantity')) {
            Schema::table('stock_taking_details', function (Blueprint $table) {
                $table->dropColumn(['system_quantity', 'variance']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_details', function (Blueprint $table) {
            $table->decimal('system_quantity', 15, 2)->default(0)->after('item_id');
            $table->decimal('variance', 15, 2)->storedAs('actual_quantity - system_quantity')->after('actual_quantity');
        });
    }
};
