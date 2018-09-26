<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Common\Fields\HtmlField;

class Category extends Model
{
    protected $guarded = [];

    public static function migrateUp()
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->integer('article_id');
            $table->integer('category_id');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });
    }
}
