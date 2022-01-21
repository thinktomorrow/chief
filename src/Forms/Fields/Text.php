<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAppend;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPrepend;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasRedactorToolbar;

class Text extends Component implements Field
{
    use HasCharacterCount;
    use HasPrepend;
    use HasAppend;
    use HasRedactorToolbar;

    protected string $view = 'chief-forms::fields.text';
    protected string $windowView = 'chief-forms::fields.text-window';

    /**
     * For input we have extra fixed set of options to make
     * sure the editor area is displayed as a single line.
     */
    private function defaultRedactorOptions(?string $locale = null): array
    {
        return [
            'maxHeight' => '56px',
            'maxWidth' => '100%',
            'enterKey' => false,
            'paragraphize' => false, // don't put surrounding p tags on save
            'toolbarExternal' => '#js-external-editor-toolbar-'.str_replace('.', '_', $this->getElementId($locale)),
        ];
    }
}
