<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Filters\Types;

class FilterType
{
    const INPUT = 'input';   // oneliner text (input)
    const SELECT = 'select';  // Select options

    /**
     * @var string
     */
    private $type;

    final public function __construct(string $type)
    {
        if (!in_array($type, [
            static::INPUT,
            static::SELECT,
        ])) {
            throw new \Exception('Invalid type identifier given [' . $type . '].');
        }

        $this->type = $type;
    }

    public static function fromString(string $type)
    {
        $class = 'Thinktomorrow\Chief\Filters\Types\\' . ucfirst($type . 'Filter');

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
