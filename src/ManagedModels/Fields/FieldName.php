<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

class FieldName
{
    private string $name;

    /** @var null|string */
    private $localizedFormat;

    private bool $withBrackets = false;

    private array $placeholders = [];

    final private function __construct(string $name)
    {
        $this->name = $this->replaceBracketsByDots($name);
    }

    public function placeholders(array $placeholders): self
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /**
     * @return static
     */
    public static function fromString(string $name): self
    {
        return new static($name);
    }

    public function get(?string $locale = null): string
    {
        $name = $this->replacePlaceholders($this->name);

        if ($locale) {
            $name = $this->getLocalized($name, $locale);
        }

        if ($this->withBrackets) {
            $name = $this->replaceDotsByBrackets($name);
        }

        return $name;
    }

    public function localizedFormat(string $localizedFormat): self
    {
        $this->validateLocalizedFormat($localizedFormat);

        $this->localizedFormat = $localizedFormat;

        return $this;
    }

    public function withBrackets(): self
    {
        $this->withBrackets = true;

        return $this;
    }

    private function getLocalized(string $name, string $locale): string
    {
        if (isset($this->localizedFormat)) {
            $name = str_replace(':name', $name, $this->localizedFormat);
        }

        return str_replace(':locale', $locale, $name);
    }

    private function replacePlaceholders(string $name): string
    {
        foreach ($this->placeholders as $placeholderKey => $value) {
            $name = str_replace(':' . $placeholderKey, $value, $name);
        }

        return $name;
    }

    /**
     * @param string $format
     */
    private function validateLocalizedFormat(string $format): void
    {
        if (false === strpos($format, ':locale')) {
            throw new \InvalidArgumentException('Invalid format for fieldname. Please provide a :locale placeholder.');
        }
    }

    private function replaceDotsByBrackets(string $value): string
    {
        if (false === strpos($value, '.')) {
            return $value;
        }

        $value = str_replace('.', '][', $value) . ']';

        return substr_replace($value, '', strpos($value, ']'), 1);
    }

    private function replaceBracketsByDots(string $value): string
    {
        return str_replace(['[', ']'], ['.', ''], $value);
    }
}
