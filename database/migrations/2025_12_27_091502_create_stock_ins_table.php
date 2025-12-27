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
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')
                ->constrained()
                ->restrictOnDelete();

            $table->integer('qty');
            $table->date('date');
            $table->enum('source', [
                'purchase',
                'return',
                'adjustment',
                'production'
            ]);

            $table->string('reference_no')->nullable();
            $table->enum('status', ['draft', 'approved', 'cancelled'])
                ->default('approved');
            $table->text('note')->nullable();
            $table->string('created_by');
            $table->string('approved_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};
