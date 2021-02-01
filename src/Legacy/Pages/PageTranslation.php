<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Legacy\Pages;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\GlobalMorphableScope;

class PageTranslation extends Model
{
    protected $table = 'page_translations';
    public $guarded = [];
    public $timestamps = true;

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function pageWithoutCollectionScope()
    {
        return $this->page()->withoutGlobalScope(GlobalMorphableScope::class);
    }
}
