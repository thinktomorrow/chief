<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Management;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Media\UploadMedia;

trait ManagesMedia
{
    public function uploadMedia(Fields $fields, Request $request)
    {
        $files = array_merge_recursive($request->input('files', []), $request->file('files', []));
        $filesOrder = $request->input('filesOrder', []);


        /** @var UploadMedia */
        app(UploadMedia::class)->fromUploadComponent($this->model, $files, $filesOrder);
    }
}
