<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Fields\Types;

class PagebuilderField extends Field
{
    public static function make(string $key)
    {
        return new static(new FieldType(FieldType::PAGEBUILDER), $key);
    }

    /**
     * Current / Default sections
     *
     * @param array $sections
     * @return $this
     */
    public function sections(array $sections)
    {
        $this->values['sections'] = $sections;

        return $this;
    }

    public function availablePages(array $availablePages)
    {
        $this->values['availablePages'] = $availablePages;

        return $this;
    }

    public function availableModules(array $availableModules)
    {
        $this->values['availableModules'] = $availableModules;

        return $this;
    }

    public function availableSets(array $availableSets)
    {
        $this->values['availableSets'] = $availableSets;

        return $this;
    }

    public function __get($key)
    {
        $value = parent::__get($key);

        // Default empty array for these following values
        if (!$value && in_array($key, ['sections','availableModules','availablePages', 'availableSets'])) {
            return [];
        }

        return $value;
    }
}
