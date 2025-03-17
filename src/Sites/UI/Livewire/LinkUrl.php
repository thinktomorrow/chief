<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

class LinkUrl
{
    public function __construct(
        public readonly string $urlRecordId,
        public readonly string $url,
        public readonly string $path,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'urlRecordId' => $this->urlRecordId,
            'url' => $this->url,
            'path' => $this->path,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new static(
            urlRecordId: $data['urlRecordId'],
            url: $data['url'],
            path: $data['path'],
        );
    }
}
