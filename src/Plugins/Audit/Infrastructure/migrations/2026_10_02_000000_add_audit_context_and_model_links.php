<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chief_audit_events', function (Blueprint $table): void {
            $table->json('context')->nullable();
        });

        Schema::create('chief_audit_event_models', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained('chief_audit_events')->cascadeOnDelete();
            $table->string('model_type', 190);
            $table->string('model_id', 190);
            $table->json('model_snapshot');
            $table->json('context');
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chief_audit_event_models');

        Schema::table('chief_audit_events', function (Blueprint $table): void {
            $table->dropColumn('context');
        });
    }
};
