<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Types;

use Thinktomorrow\Chief\Forms\Fields\Field;

class InputField extends AbstractField implements Field
{
    use AllowsCharacterCount;
    use AllowsHtmlOptions;

    public static function make(string $key): Field
    {
        return new static(new FieldType(FieldType::INPUT), $key);
    }

    public function allowsHtmlOptions(): bool
    {
        return (count($this->htmlOptions) > 0);
    }

    public function getHtmlOptions(string $key): array
    {
        // For input we have extra fixed set of options to make sure the editor area is displayed as a single line
        $defaultInputFieldOptions = [
            'maxHeight' => '56px',
            'maxWidth' => '100%',
            'enterKey' => false,
            'paragraphize' => false, // don't put surrounding p tags on save
            'toolbarExternal' => '#js-external-editor-toolbar-' . str_replace('.', '_', $key),
        ];

        return array_merge($defaultInputFieldOptions, $this->redactorMapping($this->htmlOptions));
    }
}
