<?php

namespace Chief\Trans\Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transgroup extends Model
{
    public $table = 'trans_groups';

    public static function make($slug, $label = null)
    {
        $group = new self;
        $group->slug = Str::slug($slug); // TODO: assert unique slug
        $group->label = $label?: ucfirst($slug);
        $group->save();

        return $group;
    }

    public function trans()
    {
        return $this->hasMany(Trans::class,'group_id');
    }

    public static function getAll()
    {
        return self::sequence()->get();
    }

    public function scopeSequence($query)
    {
        return $query->orderBy('sequence','ASC');
    }

    public static function findBySlug($slug)
    {
        return self::where('slug',$slug)->first();
    }
}