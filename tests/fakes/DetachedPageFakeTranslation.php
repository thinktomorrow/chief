<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Pages\Page;

class DetachedPageFakeTranslation extends Model
{
    protected $table = 'detached_page_translations';
    public $guarded = [];
    public $timestamps = true;

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
