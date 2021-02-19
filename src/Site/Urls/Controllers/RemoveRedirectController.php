<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class RemoveRedirectController
{
    public function delete(Request $request, $id)
    {
        $urlRecord = UrlRecord::find($id);

        if (! $urlRecord) {
            return response()->json(['No url record found by id ' . $id], 500);
        }

        if (! $urlRecord->isRedirect()) {
            return response()->json(['Url with id ' . $id . ' is not a redirect'], 500);
        }

        $this->pointChildRedirectsToParent($urlRecord, $urlRecord->redirect_id);

        $urlRecord->delete();

        return response()->json([
            'status' => 'ok',
        ]);
    }

    /**
     * All redirects pointing to this redirect should be pointing to the parent of this urlRecord.
     *
     * @param UrlRecord $urlRecord
     * @param $parentId
     */
    public function pointChildRedirectsToParent(UrlRecord $urlRecord, $parentId): void
    {
        if (! $urlRecord->isRedirect()) {
            return;
        }

        if ($record = UrlRecord::where('redirect_id', $urlRecord->id)->first()) {
            $record->redirect_id = $parentId;
            $record->save();
            $this->pointChildRedirectsToParent($record, $parentId);
        }
    }
}
