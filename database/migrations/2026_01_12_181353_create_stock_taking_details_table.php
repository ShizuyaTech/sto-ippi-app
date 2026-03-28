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
        Schema::create('stock_taking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_taking_session_id')->constrained()->onDelete('cascade');
            $table->string('tag_number');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->decimal('actual_quantity', 15, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->index('stock_taking_session_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_taking_details');
    }
};
