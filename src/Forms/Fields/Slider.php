<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasMinMax;

class Slider extends Component implements Field
{
    use HasMinMax;

    protected ?int $step = null;

    protected string $view = 'chief-forms::fields.slider';
    protected string $windowView = 'chief-forms::fields.text-window';

    public function step(int $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }
}
