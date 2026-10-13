<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chief_audit_events', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 190);
            $table->string('category', 100);
            $table->string('outcome', 100)->nullable();
            $table->dateTime('occurred_at')->index();
            $table->dateTime('recorded_at');
            $table->string('summary', 500)->nullable();
            $table->string('actor_type', 20);
            $table->json('actor_snapshot');
            $table->string('model_type', 190)->nullable();
            $table->string('model_id', 190)->nullable();
            $table->json('model_snapshot')->nullable();
            $table->json('context')->nullable();
            $table->boolean('has_rich_data')->default(false);
        });

        Schema::create('chief_audit_event_models', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained('chief_audit_events')->cascadeOnDelete();
            $table->string('model_type', 190);
            $table->string('model_id', 190);
            $table->json('model_snapshot');
            $table->json('context');
            $table->json('changes')->nullable();
            $table->boolean('has_rich_data')->default(false);
            $table->index(['model_type', 'model_id']);
        });

        Schema::create('chief_audit_rich_data', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->constrained('chief_audit_events')->cascadeOnDelete();
            $table->foreignId('model_link_id')->nullable()->constrained('chief_audit_event_models')->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('status', 20);
            $table->longText('content')->nullable();
            $table->json('metadata')->nullable();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chief_audit_rich_data');
        Schema::dropIfExists('chief_audit_event_models');
        Schema::dropIfExists('chief_audit_events');
    }
};
