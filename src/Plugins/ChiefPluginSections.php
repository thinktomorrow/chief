<?php

namespace Thinktomorrow\Chief\Plugins;

class ChiefPluginSections
{
    private array $sections;

    public function __construct()
    {
        $this->sections = [];
    }

    public function addFooterSection(string $viewPath): static
    {
        $this->addSection('footer', $viewPath);

        return $this;
    }

    public function getFooterSections(): array
    {
        return $this->getSection('footer');
    }

    public function addLivewireFileComponent(string $livewireTagName): static
    {
        $this->addSection('livewire_component_in_file_component', $livewireTagName);

        return $this;
    }

    public function getLivewireFileComponents(): array
    {
        return $this->getSection('livewire_component_in_file_component');
    }

    public function addLivewireFileEditAction(string $livewireFileEditActionPath): static
    {
        $this->addSection('livewire_file_edit_actions', $livewireFileEditActionPath);

        return $this;
    }

    public function getLivewireFileEditActions(): array
    {
        return $this->getSection('livewire_file_edit_actions');
    }

    private function addSection(string $key, $value): void
    {
        if (! isset($this->sections[$key])) {
            $this->sections[$key] = [];
        }

        $this->sections[$key][] = $value;
    }

    private function getSection(string $key): array
    {
        return isset($this->sections[$key]) ? $this->sections[$key] : [];
    }
}
