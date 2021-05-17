<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Site\Urls\UrlHelper;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class InternalLinksController extends Controller
{
    public function index(Request $request)
    {
        // Fetch the links for specific locale
        if ($request->has('locale')) {
            app()->setLocale($request->input('locale'));
        }

        $onlineModels = UrlHelper::onlineModels();

        $links = $onlineModels->reject(function (Visitable $model) {
            return ! $model->url();
        })->map(function ($model) {
            $name = (method_exists($model, 'menuLabel') && $model->menuLabel()) ? $model->menuLabel() : (isset($model->title) ? $model->title : $model->url());

            return [
                'name' => $name ?? $model->url(),
                'url' => $model->url(),
            ];
        });

        return response()->json($links->prepend(['name' => '...', 'url' => ''])->all());
    }
}
