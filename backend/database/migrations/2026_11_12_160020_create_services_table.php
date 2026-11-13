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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->text('name');
            $table->decimal('base_price', 12, 2);
            $table->timestampTz('created_at');
            $table->timestampTz('updated_at');
        });
        DB::statement("
        ALTER TABLE services
        ADD CONSTRAINT services_base_price_check
        CHECK (base_price >= 0)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
