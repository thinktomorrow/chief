<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu;

use Dimsav\Translatable\Translatable as BaseTranslatable;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Common\Translatable\Translatable;
use Thinktomorrow\Chief\Common\Translatable\TranslatableContract;

class MenuItem extends Model implements TranslatableContract
{
    use Translatable,
        BaseTranslatable;

    protected $translationModel = MenuItemTranslation::class;
    protected $translationForeignKey = 'menu_item_id';
    protected $translatedAttributes = [
        'label', 'url'
    ];

    public $timestamps = false;
    public $guarded = [];
}