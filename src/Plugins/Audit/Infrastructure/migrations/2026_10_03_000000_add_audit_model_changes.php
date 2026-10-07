<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chief_audit_event_models', function (Blueprint $table): void {
            $table->json('changes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('chief_audit_event_models', function (Blueprint $table): void {
            $table->dropColumn('changes');
        });
    }
};
