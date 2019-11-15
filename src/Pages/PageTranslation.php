<?php

namespace Thinktomorrow\Chief\Pages;

use Thinktomorrow\Chief\Concerns\Morphable\GlobalMorphableScope;
use Illuminate\Database\Eloquent\Model;

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
