<?php

namespace Chief\Articles;

use Chief\Common\Contracts\SluggableContract;
use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model implements SluggableContract
{
    protected $table = 'article_translations';
    public $timestamps = true;

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public static function findBySlug($slug)
    {
        return self::where('slug',$slug)->first();
    }
}
