<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Admin\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\ValidationRules\UniqueUrlSlugRule;

class LinksController
{
    use ValidatesRequests;

    public function update(Request $request)
    {
        /** @var Visitable|Model $model */
        $model = ModelReference::make($request->modelClass, (string) $request->modelId)->instance();

        $this->validate($request, ['links' => [
            'array', 'min:1', new UniqueUrlSlugRule($model, $model),], [], ['links.*' => 'taalspecifieke link'],
        ]);

        (new SaveUrlSlugs())->handle($model, $request->input('links', []));

        // Push update to homepage setting value
        // TODO: we should just fetch the homepages and push that instead...
        UrlRecord::getByModel($model)->reject(function ($record) {
            return ($record->isRedirect() || ! $record->isHomepage());
        })->each(function ($record) {
            app(ChangeHomepage::class)->onUrlChanged($record);
        });

        return response()->json([
            'message' => 'links updated',
        ], 200);
    }
}
