<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Pages\Page;

class ArticlePageWithCategories extends Page
{
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
