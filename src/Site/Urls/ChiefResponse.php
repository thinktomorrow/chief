<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\NotFoundMorphKey;

final class ChiefResponse
{
    public static function fromSlug(string $slug, $locale = null): BaseResponse
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        try {
            $urlRecord = UrlRecord::findBySlug($slug, $locale);

            if ($urlRecord->isRedirect()) {
                return static::createRedirect(
                    static::findModel($urlRecord->redirectTo())->url($locale)
                );
            }

            return new Response(
                static::findModel($urlRecord)->renderView()
            );
        } catch (\Throwable $e) {
            if (config('chief.strict')) {
                throw $e;
            }
        }

        throw new NotFoundHttpException('No url or model found for request [' . $slug . '] for locale [' . $locale . '].');
    }

    private static function findModel(UrlRecord $urlRecord): Visitable
    {
        $model = ModelReference::make($urlRecord->model_type, $urlRecord->model_id)->instance();

        if(! $model->isVisitable()) {
            throw new NotFoundHttpException('Model found for request [' . $urlRecord->slug . '] but it is not visitable.');
        }

        // TEST THE STUFF BELOW!
        // v TODO: check if model isnt archived
        // v TODO: check if model is published (and not drafted)
        // TODO: check if model isnt softdeleted
        // TODO: check if model ProvidesUrl
        // TODO: check if model has url

        // Check if preview mode is on - in that case non-published ones are allowed

        return $model;
    }

    private static function createRedirect(string $url): RedirectResponse
    {
        return new RedirectResponse($url, 301, []);
    }
}
