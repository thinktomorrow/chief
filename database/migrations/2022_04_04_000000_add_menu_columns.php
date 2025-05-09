<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('status')->default(\Thinktomorrow\Chief\Menu\MenuItemStatus::online->value);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('hidden_in_menu');
        });
    }

    public function down() {}
};
