<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;

class Textarea extends Component implements Field
{
    use HasCharacterCount;

    protected string $view = 'chief-form::fields.textarea';
    protected string $windowView = 'chief-form::fields.textarea-window';
}
