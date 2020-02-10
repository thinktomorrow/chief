<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

class MediaRequestInput
{
    private $value;

    /** @var string */
    private $locale;

    /** @var string */
    private $type;

    /** @var array */
    private $metadata;

    public function __construct($value, string $locale, string $type, array $metadata)
    {
        $this->value = $value;
        $this->locale = $locale;
        $this->type = $type;
        $this->metadata = $metadata;
    }

    public function value()
    {
        return $this->value;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function metadata($key = null)
    {
        return $key ? $this->metadata[$key] : $this->metadata;
    }
}
