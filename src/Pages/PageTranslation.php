<?php

namespace Chief\Pages;

use Chief\Common\Contracts\SluggableContract;
use Illuminate\Database\Eloquent\Model;
use Chief\Pages\Page;

class PageTranslation extends Model implements SluggableContract
{
    protected $table = 'page_translations';
    public $timestamps = true;

    public function article()
    {
        return $this->belongsTo(Page::class);
    }

    public static function findBySlug($slug)
    {
        return self::where('slug',$slug)->first();
    }
}
