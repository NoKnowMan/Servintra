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
        Schema::create('client_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_proposal_id')->constrained()->restrictOnDelete();
            $table->text('decision');
            $table->text('method')->default('phone');
            $table->foreignId('recorded_by')->constrained('company_memberships')->restrictOnDelete();
            $table->timestampTz('decided_at');
            $table->text('refusal_reason')->nullable();
            $table->unique('repair_proposal_id');
        });
        DB::statement("
        ALTER TABLE client_decisions
        ADD CONSTRAINT client_decisions_decision_check
        CHECK ( decision in ('approved', 'rejected') )
        ");
        DB::statement("
        ALTER TABLE client_decisions
        ADD CONSTRAINT client_decisions_method_check
        CHECK ( method  IN ('phone'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_decisions');
    }
};
