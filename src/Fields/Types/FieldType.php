<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

class FieldType
{
    const INPUT = 'input';   // oneliner text (input)
    const TEXT = 'text';    // Plain text (textarea)
    const NUMBER = 'number'; // number
    const RANGE = 'range'; // range slider
    const DATE = 'date';    // Timestamp input
    const PHONENUMBER = 'phonenumber';    // Timestamp input
    const HTML = 'html';    // Html text (wysiwyg)
    const SELECT = 'select';  // Select options
    const MEDIA = 'media';  // media file (slim uploader)
    const DOCUMENT = 'document';  // documents
    const RADIO = 'radio';  // radio select
    const CHECKBOX = 'checkbox';  // checkbox select
    const PAGEBUILDER = 'pagebuilder';  // the most special field there is...

    /** @var string */
    private $type;

    /**
     * FieldType constructor.
     * Type is not validated against the default available set.
     * A developer can choose to set any string as a fieldType.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type): self
    {
        $class = 'Thinktomorrow\Chief\Fields\Types\\' . ucfirst($type . 'Field');

        return new $class(new static($type));
    }

    public function get(): string
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->get();
    }
}
