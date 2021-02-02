<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Thinktomorrow\Chief\Admin\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\Site\Urls\ValidationRules\UniqueUrlSlugRule;

class LinksController
{
    use ValidatesRequests;

    public function update(Request $request)
    {
        /** @var ProvidesUrl|Model $model */
        $model = (new ModelReference($request->modelClass, (string) $request->modelId))->instance();

        $this->validate($request, ['links' => [
            'array', 'min:1', new UniqueUrlSlugRule($model, $model),], [], ['links.*' => 'taalspecifieke link']
        ]);

        (new SaveUrlSlugs())->handle($model, $request->input('links', []));

        // Push update to homepage setting value
        // TODO: we should just fetch the homepages and push that instead...
        UrlRecord::getByModel($model)->reject(function ($record) {
            return ($record->isRedirect() || !$record->isHomepage());
        })->each(function ($record) {
            app(ChangeHomepage::class)->onUrlChanged($record);
        });

        return response()->json([
            'message' => 'links updated',
        ], 200);
    }
}
