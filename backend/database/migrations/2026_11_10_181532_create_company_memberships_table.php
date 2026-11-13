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
        Schema::create('company_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->text('name')->nullable();
            $table->text('role');
            $table->boolean('is_active')->default(true);
            $table->timestampTz('created_at');
            $table->unique(['user_id', 'company_id']);
            $table->unique(['company_id', 'id']);
        });
        DB::statement("
        ALTER TABLE company_memberships
        ADD CONSTRAINT company_memberships_role_check
        CHECK (role IN ('owner', 'dispatcher', 'master', 'client'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_memberships');
    }
};
