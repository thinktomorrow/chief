<?php

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\States\PageState;
use Illuminate\Database\Migrations\Migration;

class RemoveFeaturedColumn extends Migration
{
    public function up()
    {
        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('featured');
        });
    }

    public function down()
    {
        //
    }
}
