<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\TranslatableFields;

class FieldType
{
    const INPUT  = 'input';   // oneliner text (input)
    const TEXT   = 'text';    // Plain text (textarea)
    const DATE   = 'date';    // Timestamp input
    const HTML   = 'html';    // Html text (wysiwyg)
    const SELECT = 'select';  // Select options

    /**
     * @var string
     */
    private $type;

    public function __construct(string $type)
    {
        if (!in_array($type, [static::INPUT, static::TEXT, static::HTML, static::SELECT, static::DATE])) {
            throw new \Exception('Invalid type identifier given ['.$type.'].');
        }

        $this->type = $type;
    }

    public static function fromString(string $type)
    {
        $class = 'Thinktomorrow\Chief\Common\TranslatableFields\\'.ucfirst($type.'Field');
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
