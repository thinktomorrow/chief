<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\FieldName;

class LocalizedFieldName
{
    const DEFAULT_TEMPLATE = 'trans.:locale.:name';

    /**
     * The default structure of a localized form key. The default
     * 'trans.:locale.:name' translates to trans[nl][title].
     */
    private static string $default_template = self::DEFAULT_TEMPLATE;

    private string $template;

    /**
     * Render a multi-dimensional formkey with brackets.
     * e.g. trans.nl.title -> trans[nl][title].
     */
    private bool $bracketed = false;
    private array $replacements = [];

    final private function __construct()
    {
        $this->template = self::$default_template;
    }

    public static function make(): self
    {
        return new static();
    }

    public static function setDefaultTemplate(string $default_template): void
    {
        static::$default_template = $default_template;
    }

    public static function getDefaultTemplate(): string
    {
        return static::$default_template;
    }

    public function get(string $value, ?string $locale = null, bool $cleanupUnusedPlaceholders = true): string
    {
        $replacements = $this->replacements;

        if ($locale) {
            $replacements = array_merge(['locale' => $locale], $replacements);
        }

        $value = $this->handleReplacements($value, $replacements, $cleanupUnusedPlaceholders);

        return ($this->bracketed)
            ? $this->replaceDotsByBrackets($value)
            : $this->replaceBracketsByDots($value);
    }

    public function matrix(string $value, array $locales): array
    {
        $keys = [];

        foreach ($locales as $locale) {
            $keys[] = $this->get($value, $locale, false);
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

    public function getTemplate(): string
    {
        return $this->template;
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

    private function handleReplacements(string $value, array $replacements, bool $cleanupUnusedPlaceholders = true): string
    {
        $value = $this->replaceName($value);

        foreach ($replacements as $from => $to) {
            $value = str_replace(':'.$from, $to, $value);
        }

        // Cleanup up any non-replaced placeholders
        if ($cleanupUnusedPlaceholders) {
            $value = preg_replace('#:([a-zA-Z]*)#', '', $value);
            $value = str_replace('..', '.', trim($value, '.'));
        }

        return $value;
    }

    private function addLocaleReplacement(string $locale): void
    {
        $this->replacements = array_merge([
            'locale' => $locale,
        ], $this->replacements);
    }

    private function replaceName(string $value): string
    {
        return str_replace(':name', $value, $this->template);
    }

    private function replaceDotsByBrackets(string $value): string
    {
        return FieldNameHelpers::replaceDotsByBrackets($value);
    }

    private function replaceBracketsByDots(string $value): string
    {
        return FieldNameHelpers::replaceBracketsByDots($value);
    }
}
