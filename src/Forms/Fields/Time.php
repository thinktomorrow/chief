<?php

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStep;

class Time extends Component implements Field
{
    use HasMinMax;
    use HasStep;

    protected string $view = 'chief-form::fields.time';

    protected string $previewView = 'chief-form::previews.fields.time';

    public static function make(string $key)
    {
        $model = new static($key);

        $model->step(60 * 5); // 5 mins as default steps

        return $model;
    }

    public function getValue(?string $locale = null): mixed
    {
        $value = parent::getValue($locale);

        return $value instanceof \DateTime
            ? $value->format('H:i')
            : $value;
    }
}
