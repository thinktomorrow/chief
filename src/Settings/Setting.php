<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $table = 'settings';
    public $timestamps = false;
    public $guarded = [];
}