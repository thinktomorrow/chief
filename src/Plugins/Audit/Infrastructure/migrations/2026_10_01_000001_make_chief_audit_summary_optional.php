<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chief_audit_events', function (Blueprint $table): void {
            $table->string('summary', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('chief_audit_events')->whereNull('summary')->update(['summary' => DB::raw('type')]);

        Schema::table('chief_audit_events', function (Blueprint $table): void {
            $table->string('summary', 500)->nullable(false)->change();
        });
    }
};
