<?php

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\States\PageState;
use Illuminate\Database\Migrations\Migration;

class RemoveCollectionTypeFromMenuItems extends Migration
{
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('collection_type');
        });
    }

    public function down()
    {
        //
    }
}
