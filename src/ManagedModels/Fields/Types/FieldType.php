<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

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
    const FILE = 'file';  // regular file
    const IMAGE = 'image';  // image (slim uploader)
    const RADIO = 'radio';  // radio select
    const CHECKBOX = 'checkbox';  // checkbox select
    const PAGEBUILDER = 'pagebuilder';  // the most special field there is...
    const FRAGMENT = 'fragment';
    const PAGE = 'page'; // select a page (also a special field)

    /** @var string */
    private $type;

    /**
     * FieldType constructor.
     * Type is not validated against the default available set.
     * A developer can choose to set any string as a fieldType.
     *
     * @param string $type
     */
    final public function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type): self
    {
        return new static($type);
    }

    public function get(): string
    {
        return $this->type;
    }

    public function equalsAny(array $types): bool
    {
        foreach ($types as $type) {
            if ($this->equals(FieldType::fromString($type))) {
                return true;
            }
        }

        return false;
    }

    public function equals(self $type): bool
    {
        return ((string)$type === (string)$this->type && static::class === get_class($type));
    }

    public function __toString()
    {
        return $this->get();
    }
}
