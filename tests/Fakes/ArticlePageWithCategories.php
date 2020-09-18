<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ArticlePageWithCategories extends Page
{
    protected static $managedModelKey = "articles_with_category_fake";
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
