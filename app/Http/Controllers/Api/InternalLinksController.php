<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlHelper;

class InternalLinksController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();

        // Fetch the links for specific locale
        if ($request->has('locale')) {
            $locale = $request->input('locale');
            app()->setLocale($locale);
        }

        $onlineModels = UrlHelper::onlineModels();

        $links = $onlineModels->reject(function (ProvidesUrl $model) {
            return !$model->url();
        })->map(function ($model) {
            $name = (method_exists($model, 'menuLabel') && $model->menuLabel()) ? $model->menuLabel() : (isset($model->title) ? $model->title : $model->url());
            return [
                'name' => $name ??  $model->url(),
                'url' => $model->url(),
            ];
        });

        return response()->json($links->prepend(['name' => '...', 'url' => ''])->all());
    }
}
