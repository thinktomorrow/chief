<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Locale;

use Thinktomorrow\Chief\Forms\Fields\Helpers\FormKey;

class LocalizedFormKey
{
    public const DEFAULT_TEMPLATE = 'trans.:locale.:name';

    private string $template;

    /**
     * Render a multi-dimensional formkey with brackets.
     * e.g. trans.nl.title -> trans[nl][title].
     */
    private bool $bracketed = false;
    private array $replacements = [];

    final private function __construct()
    {
        $this->template = self::DEFAULT_TEMPLATE;
    }

    public static function make(): self
    {
        return new static();
    }

    public function get(string $value, ?string $locale = null): string
    {
        if ($locale) {
            $value = $this->handleReplacements($value, $locale);
        }

        return ($this->bracketed)
            ? $this->replaceDotsByBrackets($value)
            : $this->replaceBracketsByDots($value);
    }

    public function matrix(string $value, array $locales): array
    {
        $keys = [];

        foreach ($locales as $locale) {
            $keys[] = $this->get($value, $locale);
        }

        return $keys;
    }

    public function replace(string $search, string $replace): self
    {
        $this->replacements[$search] = $replace;

        return $this;
    }

    public function template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function bracketed(): self
    {
        $this->bracketed = true;

        return $this;
    }

    public function dotted(): self
    {
        $this->bracketed = false;

        return $this;
    }

    private function handleReplacements(string $value, string $locale): string
    {
        $value = $this->replaceName($value);

        $replacements = array_merge([
            'locale' => $locale,
        ], $this->replacements);

        foreach ($replacements as $from => $to) {
            $value = str_replace(':'.$from, $to, $value);
        }

        return $value;
    }

    private function replaceName(string $value): string
    {
        return str_replace(':name', $value, $this->template);
    }

    private function replaceDotsByBrackets(string $value): string
    {
        return FormKey::replaceDotsByBrackets($value);
    }

    private function replaceBracketsByDots(string $value): string
    {
        return FormKey::replaceBracketsByDots($value);
    }
}
