<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Thinktomorrow\Chief\Concerns\Sluggable\SluggableContract;
use Illuminate\Database\Eloquent\Model;

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
