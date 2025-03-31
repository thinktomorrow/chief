<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCharacterCount;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPrependAppend;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasRedactorToolbar;

class Text extends Component implements Field
{
    use HasCharacterCount;
    use HasPrependAppend;
    use HasRedactorToolbar;

    protected string $view = 'chief-form::fields.text';

    protected string $previewView = 'chief-form::previews.fields.text';

    protected string $viewWithRedactor = 'chief-form::fields.text-with-redactor';

    public function getView(): string
    {
        if ($this->hasRedactorOptions()) {
            return $this->viewWithRedactor;
        }

        return parent::getView();
    }

    /**
     * For input we have extra fixed set of options to make
     * sure the editor area is displayed as a single line.
     */
    private function defaultRedactorOptions(?string $locale = null): array
    {
        return [
            'buttons' => [],
            'plugins' => [],
            'maxWidth' => '100%',
            'enterKey' => false,
            'paragraphize' => false,
            'toolbarExternal' => '#js-external-editor-toolbar-'.str_replace('.', '_', $this->getElementId($locale)),
        ];
    }
}
