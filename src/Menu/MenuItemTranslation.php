<?php

namespace Thinktomorrow\Chief\Menu;

use Thinktomorrow\Chief\Concerns\Sluggable\SluggableContract;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Pages\Page;

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
