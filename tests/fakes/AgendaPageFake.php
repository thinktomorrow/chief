<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Concerns\HasPeriod\HasPeriodTrait;

class AgendaPageFake extends Page
{
    use HasPeriodTrait;
    
    public static function migrateUp()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->timestamp('start_at')->nullable()->default(NULL);
            $table->timestamp('end_at')->nullable()->default(NULL);
        });
    }

    public function url($locale = null): string
    {
        return route('articles.show', $this->slug);
    }

    public static function customTranslatableFields(): array
    {
        return [
            HtmlField::make('content'),
        ];
    }
}
