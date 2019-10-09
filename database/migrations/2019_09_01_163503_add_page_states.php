<?php

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPageStates extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('current_state')->after('morph_key')->default(\Thinktomorrow\Chief\States\PageState::DRAFT);
        });

        $this->convertOldStates();

        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('archived_at');
        });

        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('published');
        });

        Schema::table('pages', function(Blueprint $table){
            $table->dropColumn('publication');
        });
    }

    private function convertOldStates()
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
    }

    public function down()
    {
        //
    }
}
