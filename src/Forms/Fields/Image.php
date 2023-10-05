<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Image extends File
{
    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->acceptedMimeTypes([
            'image/jpeg', 'image/png', 'image/svg+xml', 'image/webp',
        ]);
    }
}
