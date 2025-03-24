<?php

namespace Thinktomorrow\Chief\Forms\Fields\Repeat\Livewire;

use Illuminate\Support\Collection;

trait WithRepeatSections
{
    public Collection $sections;

    private function getListenersWithRepeatSections()
    {
        return [
            'section-updated-'.$this->getId() => 'onSectionUpdated',
            'section-added-'.$this->getId() => 'onSectionAdded',
            'section-deleting-'.$this->getId() => 'onSectionDeleting',
        ];
    }

    public function addSection(string $parentId, int $order): void
    {
        //
    }

    public function onSectionDeleting(string $sectionId): void
    {
        // Remove section...
    }

    public function reorder($sectionIds)
    {
        $this->sections = $this->sections
            ->map(function (SectionDto $section) use ($sectionIds) {
                $section->order = array_search($section->sectionId, $sectionIds);

                return $section;
            })
            ->sortBy('order');
    }

    private function findSection(string $sectionId): SectionDto
    {
        return $this->sections->first(fn (SectionDto $section) => $section->sectionId === $sectionId);
    }
}
