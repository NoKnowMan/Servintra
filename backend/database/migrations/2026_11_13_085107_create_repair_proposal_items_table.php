<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_proposal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_proposal_id')->constrained()->restrictOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->restrictOnDelete();
            $table->text('name');
            $table->decimal('price', 12, 2);
        });
        DB::statement("
        ALTER TABLE repair_proposal_items
        ADD CONSTRAINT repair_proposal_items_price_check
        CHECK ( price >= 0 )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_proposal_items');
    }
};
