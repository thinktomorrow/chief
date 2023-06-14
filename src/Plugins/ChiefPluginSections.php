<?php

namespace Thinktomorrow\Chief\Plugins;

use Illuminate\Contracts\Support\Htmlable;

class ChiefPluginSections
{
    private array $sections;

    public function __construct()
    {
        $this->sections = [];
    }

    public function addFooterSection(Htmlable $value): static
    {
        $this->addSection('footer', $value);

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

    private function addSection(string $key, $value): void
    {
        if(!isset($this->sections[$key])) {
            $this->sections[$key] = [];
        }

        $this->sections[$key][] = $value;
    }

    private function getSection(string $key): array
    {
        return isset($this->sections[$key]) ? $this->sections[$key] : [];
    }

}
