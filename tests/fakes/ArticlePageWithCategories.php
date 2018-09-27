<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Common\Fields\HtmlField;
use Thinktomorrow\Chief\Common\Fields\InputField;
use Thinktomorrow\Chief\Common\Fields\SelectField;
use Thinktomorrow\Chief\Pages\Page;

class ArticlePageWithCategories extends Page
{
    public function customFields(): array
    {
        return [
            InputField::make('custom'),
            SelectField::make('categories')->options(Category::all()->pluck('title', 'id')->toArray()),

        ];
    }

    public function saveCategoriesField($value)
    {
        $this->categories()->sync($value);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'article_category', 'article_id', 'category_id');
    }

    public static function migrateUp()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('custom')->nullable();
        });
    }
}
