<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\TestSupport;

use Illuminate\Database\Eloquent\Model;

class ModelWithAstrotomicTranslationsTranslation extends Model
{
    public $table = 'astrotomic_model_translations';

    public $guarded = [];

    private $translatedAttributes = [
        'title_trans',
    ];
}
