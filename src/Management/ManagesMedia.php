<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\UploadMedia;

trait ManagesMedia
{
    public function uploadMedia(Fields $fields, Request $request)
    {
        $files = array_merge_recursive($request->get('files', []), $request->file('files', []));
        $filesOrder = $request->get('filesOrder', []);

        /** @var UploadMedia */
        app(UploadMedia::class)->fromUploadComponent($this->model, $files, $filesOrder);
    }
}
