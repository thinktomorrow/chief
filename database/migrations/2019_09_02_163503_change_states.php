<?php

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStates extends Migration
{
    public function up()
    {
        foreach(Page::withoutGlobalScopes()->get() as $page)
        {
            if($page->archived_at != null)
            {
                $page->state = 'archived';

            }elseif($page->published)
            {
                $page->state = 'published';
            }else{
                $page->state = 'draft';
            }
            $page->save();
        }

        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('archived_at');
            $table->dropColumn('published');
            $table->dropColumn('publication');
        });
    }

    public function down()
    {
        //
    }
}
