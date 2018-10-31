<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields\Types;

class FieldType
{
    const INPUT = 'input';   // oneliner text (input)
    const TEXT = 'text';    // Plain text (textarea)
    const DATE = 'date';    // Timestamp input
    const HTML = 'html';    // Html text (wysiwyg)
    const SELECT = 'select';  // Select options
    const MEDIA = 'media';  // media file (slim uploader)
    const DOCUMENT = 'document';  // documents
    const RADIO = 'radio';  // media file (slim uploader)
    const PAGEBUILDER = 'pagebuilder';  // the most special field there is...

    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        if (!in_array($type, [
            static::INPUT,
            static::TEXT,
            static::HTML,
            static::SELECT,
            static::DATE,
            static::MEDIA,
            static::DOCUMENT,
            static::RADIO,
            static::PAGEBUILDER,
        ])) {
            throw new \Exception('Invalid type identifier given [' . $type . '].');
        }

        $this->type = $type;
    }

    public static function fromString(string $type)
    {
        $class = 'Thinktomorrow\Chief\Fields\Types\\' . ucfirst($type . 'Field');

        return new $class(new static($type));
    }

    public function get()
    {
        return $this->type;
    }

    public function __toString()
    {
        return $this->get();
    }
}
