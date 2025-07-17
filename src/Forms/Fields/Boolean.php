<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasToggleDisplay;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class Boolean extends Component implements Field
{
    use HasToggleDisplay;

    protected string $view = 'chief-form::fields.checkbox';

    protected string $previewView = 'chief-form::previews.fields.checkbox';

    private string $optionLabel;

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->showAsToggle();
        $this->default(false);
    }

    public function option(string $optionLabel): static
    {
        $this->optionLabel = $optionLabel;

        return $this;
    }

    public function getOptions(?string $locale = null): array
    {
        $options = PairOptions::toPairs([
            true => $this->optionLabel ?? 'Ja',
        ]);

        return $options;
    }
}
