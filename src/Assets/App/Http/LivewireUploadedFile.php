<?php

namespace Thinktomorrow\Chief\Assets\App\Http;

use Illuminate\Http\UploadedFile;

class LivewireUploadedFile extends UploadedFile
{
    /**
     * For file validation the file rules check internally if a file is a valid upload.
     * Files uploaded via Livewire are considered invalid since the POST request
     * does not contain the file binaries. The files are already posted prior
     * to form submission.
     */
    public function isValid(): bool
    {
        return true;
    }
}
