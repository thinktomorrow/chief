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
            $table->boolean('has_rich_data')->default(false);
        });
        Schema::table('chief_audit_event_models', function (Blueprint $table): void {
            $table->boolean('has_rich_data')->default(false);
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
        Schema::table('chief_audit_event_models', function (Blueprint $table): void {
            $table->dropColumn('has_rich_data');
        });
        Schema::table('chief_audit_events', function (Blueprint $table): void {
            $table->dropColumn('has_rich_data');
        });
    }
};
