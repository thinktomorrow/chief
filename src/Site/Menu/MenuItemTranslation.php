<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Shared\Concerns\Sluggable\SluggableContract;

class MenuItemTranslation extends Model implements SluggableContract
{
    protected $table = 'menu_item_translations';
    public $timestamps = false;
    public $guarded = [];

    public static function findBySlug($slug)
    {
        return self::where('label', $slug)->first();
    }
}
