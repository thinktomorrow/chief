<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Common\TranslatableFields;

class FieldType
{
    const INPUT = 'input'; // oneliner text (input)
    const TEXT = 'text'; // Plain text (textarea)
    const HTML = 'html'; // Html text (wysiwyg)

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        if (!in_array($type, [static::INPUT, static::TEXT, static::HTML])) {
            throw new \Exception('Invalid type identifier given ['.$type.'].');
        }

        $this->type = $type;
    }

    public static function fromString(string $type)
    {
        return new static($type);
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
