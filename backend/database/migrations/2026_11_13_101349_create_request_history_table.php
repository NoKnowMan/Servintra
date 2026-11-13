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
        Schema::create('request_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_request_id')->constrained()->restrictOnDelete();
            $table->foreignId('performed_by')->constrained('company_memberships')->restrictOnDelete();
            $table->text('action');
            $table->text('from_status')->nullable();
            $table->text('to_status')->nullable();
            $table->foreignId('previous_master_id')->nullable()->constrained('company_memberships')->restrictOnDelete();
            $table->foreignId('new_master_id')->nullable()->constrained('company_memberships')->restrictOnDelete();
            $table->foreignId('repair_proposal_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('client_decision_id')->nullable()->constrained()->restrictOnDelete();
            $table->text('reason')->nullable();
            $table->timestampTz('created_at');
        });
        DB::statement("
        ALTER TABLE request_history
        ADD CONSTRAINT request_history_action_check
        CHECK (action in ('request_created', 'request_updated', 'master_assigned', 'master_reassigned',
            'assignment_declined', 'diagnostics_started', 'proposal_saved', 'approval_requested', 'client_decision_recorded',
            'repair_started', 'repair_completed', 'request_cancelled'))
        ");
        DB::statement("
        ALTER TABLE request_history
        ADD CONSTRAINT request_history_from_status_check
        CHECK (from_status IN (
            'new', 'assigned', 'diagnostics', 'awaiting_approval', 'repair', 'completed', 'cancelled'
        ))
        ");
        DB::statement("
        ALTER TABLE request_history
        ADD CONSTRAINT request_history_to_status_check
        CHECK (to_status IN (
            'new', 'assigned', 'diagnostics', 'awaiting_approval', 'repair', 'completed', 'cancelled'
        ))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_history');
    }
};
