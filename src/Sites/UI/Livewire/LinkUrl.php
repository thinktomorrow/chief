<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

class LinkUrl
{
    public function __construct(
        public readonly string $id,
        public readonly string $url,
        public readonly string $slug,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'slug' => $this->slug,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new static(
            id: $data['id'],
            url: $data['url'],
            slug: $data['slug'],
        );
    }
}
