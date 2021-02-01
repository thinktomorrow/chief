<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Legacy\Pages\Page;
use Illuminate\Database\Migrations\Migration;

class AddPageStates extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('current_state')->after('morph_key')->default(PageState::DRAFT);
        });

        // $this->convertOldStates();

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('published');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('publication');
        });
    }

    private function convertOldStates()
    {
        foreach (Page::withoutGlobalScopes()->get() as $page) {
            if ($page->archived_at != null) {
                $page->current_state = PageState::ARCHIVED;
            } elseif ($page->published) {
                $page->current_state = PageState::PUBLISHED;
            } else {
                $page->current_state = PageState::DRAFT;
            }
            $page->save();
        }
    }

    public function down()
    {
        //
    }
}
