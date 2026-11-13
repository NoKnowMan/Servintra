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
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_id');
            $table->foreignId('created_by');
            $table->text('appliance_type');
            $table->text('appliance_name')->nullable();
            $table->text('appliance_model')->nullable();
            $table->text('problem_description');
            $table->text('service_method');
            $table->text('visit_address')->nullable();
            $table->text('status')->default('new');
            $table->foreignId('assigned_master_id')->nullable();
            $table->foreignId('current_proposal_id')->nullable();
            $table->text('diagnosis_result')->nullable();
            $table->boolean('model_unidentifiable')->default(false);
            $table->timestampTz('expected_completion_at')->nullable();
            $table->text('repair_result')->nullable();
            $table->boolean('work_completion_confirmed')->default(false);
            $table->text('refusal_reason')->nullable();
            $table->timestampTz('closed_at')->nullable();
            $table->timestampTz('created_at');
            $table->timestampTz('updated_at');
            $table->foreign(['company_id', 'client_id'])
                ->references(['company_id', 'id'])
                ->on('clients')
                ->restrictOnDelete();
            $table->foreign(['company_id', 'created_by'])
                ->references(['company_id', 'id'])
                ->on('company_memberships')
                ->restrictOnDelete();
            $table->foreign(['company_id', 'assigned_master_id'])
                ->references(['company_id', 'id'])
                ->on('company_memberships')
                ->restrictOnDelete();
            $table->index(['company_id', 'created_at', 'id']);
            $table->index(['company_id', 'status', 'created_at', 'id']);
            $table->index(['company_id', 'assigned_master_id', 'created_at', 'id']);
            $table->index(['client_id', 'created_at', 'id']);
        });
        DB::statement("
        ALTER TABLE repair_requests
        ADD CONSTRAINT repair_requests_status_check
        CHECK (status IN (
            'new', 'assigned', 'diagnostics', 'awaiting_approval', 'repair', 'completed', 'cancelled'
        ))
        ");
        DB::statement("
        ALTER TABLE repair_requests
        ADD CONSTRAINT repair_requests_service_method_check
        CHECK (service_method IN ('workshop', 'on_site'))
        ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
    }
};
