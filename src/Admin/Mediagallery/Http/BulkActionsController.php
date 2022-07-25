<?php

namespace Thinktomorrow\Chief\Admin\Mediagallery\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Admin\Mediagallery\Application\RemovalAction;
use Thinktomorrow\Chief\Admin\Mediagallery\Application\ZipAction;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class BulkActionsController extends Controller
{
    /** @var ZipAction */
    private $zipAction;

    /** @var RemovalAction */
    private $removalAction;

    public function __construct(ZipAction $zipAction, RemovalAction $removalAction)
    {
        $this->zipAction = $zipAction;
        $this->removalAction = $removalAction;
    }

    public function bulk(Request $request)
    {
        if ($request->input('type') == 'download') {
            return $this->download($request);
        }

        if ($request->input('type') == 'remove') {
            return $this->remove($request);
        }
    }

    private function download(Request $request)
    {
        $filename = Str::slug(config('app.name')) . '_assets_' . date('YmdHis') . '.zip';

        // TODO: do the response here instead of the zip action itself
        return $this->zipAction->handle($filename, $request);
    }

    private function remove(Request $request)
    {
        $result = $this->removalAction->handle($request);

        if ($result) {
            return redirect()->back()->with('messages.success', 'De mediabestanden zijn verwijderd');
        }

        return redirect()->back()->with('messages.error', 'Er ging iets mis, er zijn geen bestanden verwijderd. Mogelijks probeerde je een bestand te verwijderen dat nog gebruikt wordt.');
    }
}
