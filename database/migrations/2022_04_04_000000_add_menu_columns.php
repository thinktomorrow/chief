<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function(Blueprint $table){
            $table->string('status')->default(\Thinktomorrow\Chief\Site\Menu\MenuItemStatus::online->value);
        });

        Schema::table('menu_items', function(Blueprint $table){
            $table->dropColumn('hidden_in_menu');
        });
    }

    public function down()
    {
    }
};
