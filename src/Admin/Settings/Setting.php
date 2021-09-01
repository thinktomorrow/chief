<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public const HOMEPAGE = 'homepage';

    public $table = 'settings';
    public $timestamps = false;
    public $guarded = [];
    public $casts = [
        'value' => 'json',
    ];

    public static function findByKey(string $key)
    {
        return static::where('key', $key)->first();
    }
}
