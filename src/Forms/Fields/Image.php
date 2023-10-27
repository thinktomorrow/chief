<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

class Image extends File
{
    //    protected string $view = 'chief-form::fields.file';
    //    protected string $windowView = 'chief-form::fields.image-window';

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->acceptedMimeTypes([
            'image/jpeg', 'image/png', 'image/webp',
            'image/gif', 'image/svg+xml',
        ]);
    }
}
