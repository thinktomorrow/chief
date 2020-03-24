<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\DynamicAttributes\HasDynamicAttributes;

class FragmentModel extends Model implements HasAsset
{
    use HasDynamicAttributes;
    use AssetTrait;

    protected $dynamicKeys = ['*'];
    protected $dynamicKeysBlacklist = ['id', 'key', 'order', 'owner_type', 'owner_id'];

    public $table = 'fragments';
    public $timestamps = false;
    public $guarded = [];
}
