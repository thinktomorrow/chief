<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DetachedPageFake extends Page
{
    public $translatedAttributes = ['question','title','slug'];

    protected $translationModel = DetachedPageFakeTranslation::class;

    public static function migrateUp()
    {
        Schema::create('detached_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('detached_page_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned();
            $table->string('locale');
            $table->string('question');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }
}
