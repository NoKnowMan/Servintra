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
        Schema::create('repair_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained()->restrictOnDelete();
            $table->integer('version');
            $table->decimal('diagnosis_price', 12, 2);
            $table->foreignId('created_by')->constrained("company_memberships")->restrictOnDelete();
            $table->timestampTz('created_at');
            $table->unique(['repair_request_id', 'version']);
            $table->unique(['repair_request_id', 'id']);
        });
        DB::statement("
        ALTER TABLE repair_proposals
        ADD CONSTRAINT repair_proposals_version_check
        CHECK ( version > 0 )
        ");
        DB::statement("
        ALTER TABLE repair_proposals
        ADD CONSTRAINT repair_proposals_diagnosis_price_check
        CHECK ( diagnosis_price >= 0 )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_proposals');
    }
};
