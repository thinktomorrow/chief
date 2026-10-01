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
            $table->string('summary', 500);
            $table->string('actor_type', 20);
            $table->json('actor_snapshot');
            $table->string('model_type', 190)->nullable();
            $table->string('model_id', 190)->nullable();
            $table->json('model_snapshot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chief_audit_events');
    }
};
