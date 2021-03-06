<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Mediagallery\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Application\DeleteAsset;
use Thinktomorrow\AssetLibrary\Exceptions\FileNotAccessibleException;
use Thinktomorrow\Chief\Admin\Audit\Audit;

final class RemovalAction
{
    /** @var DeleteAsset */
    private $deleteAsset;

    public function __construct(DeleteAsset $deleteAsset)
    {
        $this->deleteAsset = $deleteAsset;
    }

    public function handle(Request $request): bool
    {
        // Strict protection enabled: we won't remove assets who are still being used...
        $assetIds = collect($request->input('asset_ids', []))->reject(function ($assetId) {
            return DB::table('asset_pivots')
                ->where('asset_id', $assetId)
                ->where('unused', 0)
                ->exists();
        })->toArray();

        if ($assetIds) {
            foreach ($assetIds as $k => $assetId) {
                try {
                    $this->deleteAsset->remove($assetId);
                } catch (FileNotAccessibleException $e) {
                    unset($assetIds[$k]); // So our count of removed assets is correct in the log.
                }
            }

            Audit::activity()->log('removed ' . count($assetIds) . ' assets from the mediagallery.');

            return true;
        }

        return false;
    }
}
