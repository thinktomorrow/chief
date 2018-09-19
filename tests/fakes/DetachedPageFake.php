<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\TranslatableFields\InputField;

class DetachedPageFake extends Page
{
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
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            $table->string('question');
            $table->timestamps();
        });
    }

    public static function customTranslatableFields(): array
    {
        return [
            'question' => InputField::make(),
        ];
    }

    public static function defaultTranslatableFields(): array
    {
        return [];
    }
}
