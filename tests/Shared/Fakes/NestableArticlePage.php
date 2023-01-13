<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NestableArticlePage extends ArticlePage
{
    public function isNestable(): bool
    {
        return true;
    }

    public static function migrateUp()
    {
        Schema::create('article_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->json('values')->nullable();
            $table->string('current_state')->default('draft');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
