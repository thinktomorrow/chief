<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

class PagebuilderField extends AbstractField implements Field
{
    /** @var array */
    private $sections;

    /** @var array */
    private $availablePages;

    /** @var array */
    private $availableModules;

    /** @var array */
    private $availableSets;

    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::PAGEBUILDER), $key))
            ->view('chief::back._fields.pagebuilder')
            ->sections([])
            ->availableModules([])
            ->availablePages([])
            ->availableSets([]);
    }

    /**
     * Current / Default sections
     *
     * @param array $sections
     * @return $this
     */
    public function sections(array $sections)
    {
        $this->sections = $sections;

        return $this;
    }

    public function availablePages(array $availablePages)
    {
        $this->availablePages = $availablePages;

        return $this;
    }

    public function availableModules(array $availableModules)
    {
        $this->availableModules = $availableModules;

        return $this;
    }

    public function availableSets(array $availableSets)
    {
        $this->availableSets = $availableSets;

        return $this;
    }

    public function getSections(): array
    {
        return $this->sections;
    }

    public function getAvailableModules(): array
    {
        return $this->availableModules;
    }

    public function getAvailablePages(): array
    {
        return $this->availablePages;
    }

    public function getAvailableSets(): array
    {
        return $this->availableSets;
    }
}
