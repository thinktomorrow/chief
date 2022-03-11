<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages\Astrotomic;

use Illuminate\Database\Eloquent\Model;

class QuoteWithAstrotomicTranslationsTranslation extends Model
{
    public $table = 'quote_translations';
    public $guarded = [];
    private $translatedAttributes = [
        'title_trans',
    ];
}
