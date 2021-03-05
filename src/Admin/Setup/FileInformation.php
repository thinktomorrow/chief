<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Filesystem\Filesystem;

final class FileInformation
{
    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }
}
