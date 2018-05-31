<?php

namespace Thinktomorrow\Chief\Menu;

use Thinktomorrow\Chief\Common\Contracts\SluggableContract;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Pages\Page;

class MenuItemTranslation extends Model
{
    protected $table = 'menu_item_translations';
    public $timestamps = false;
    public $guarded = [];
}
