<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields\Types;

class PagebuilderField extends AbstractField implements Field
{
    private array $sections;
    private array $availablePages;
    private array $availableModules;
    private array $availableSets;

    public static function make(string $key): Field
    {
        return (new static(new FieldType(FieldType::PAGEBUILDER), $key))
            ->view('chief::managers.fieldtypes.pagebuilder')
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

    /**
     * @return static
     */
    public function availablePages(array $availablePages): self
    {
        $this->availablePages = $availablePages;

        return $this;
    }

    /**
     * @return static
     */
    public function availableModules(array $availableModules): self
    {
        $this->availableModules = $availableModules;

        return $this;
    }

    /**
     * @return static
     */
    public function availableSets(array $availableSets): self
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
