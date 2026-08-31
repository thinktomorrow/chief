<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasStep;

class Datetime extends Component implements Field
{
    use HasMinMax;
    use HasStep;

    protected string $view = 'chief-form::fields.datetime';

    protected string $previewView = 'chief-form::previews.fields.datetime';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->step(60); // Minute precision, so the seconds spinner stays hidden
    }

    /**
     * A datetime-local input only accepts a T as date and time separator. Without it
     * the browser considers the value invalid and renders an empty input.
     */
    public function getValue(?string $locale = null): mixed
    {
        $value = parent::getValue($locale);

        return $value instanceof \DateTimeInterface
            ? $value->format('Y-m-d\TH:i')
            : $value;
    }
}
