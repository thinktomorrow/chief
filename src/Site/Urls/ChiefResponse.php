<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\NotFoundMorphKey;

final class ChiefResponse
{
    public static function fromSlug(string $slug, $locale = null): BaseResponse
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        try {
            $urlRecord = UrlRecord::findBySlug($slug, $locale);

            $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

            if ($urlRecord->isRedirect()) {

                // If model is not found, it probably means it is archived or removed
                // So we detect the model based on the redirect target url.
                if (! $model) {
                    $targetUrlRecord = $urlRecord->redirectTo();

                    $targetModel = Morphables::instance($targetUrlRecord->model_type)->find($targetUrlRecord->model_id);

                    if (! $targetModel) {
                        throw new ArchivedUrlException('Corrupt target model for this url request. Model by reference [' . $targetUrlRecord->model_type . '@' . $targetUrlRecord->model_id . '] has probably been archived or deleted.');
                    }

                    return static::createRedirect($targetModel->url($locale));
                }

                return static::createRedirect($model->url($locale));
            }

            if (! $model) {
                throw new ArchivedUrlException('Corrupt target model for this url request. Model by reference [' . $urlRecord->model_type . '@' . $urlRecord->model_id . '] has probably been archived or deleted.');
            }

            if (public_method_exists($model, 'isPublished') && ! $model->isPublished()) {

                /** When admin is logged in and this request is in preview mode, we allow the view */
                if (! PreviewMode::fromRequest()->check()) {
                    throw new NotFoundHttpException('Model found for request [' . $slug . '] but it is not published.');
                }
            }

            return new Response($model->renderView(), 200);
        } catch (UrlRecordNotFound | NotFoundMorphKey | ArchivedUrlException $e) {
            if (config('chief.strict')) {
                throw $e;
            }
        }

        throw new NotFoundHttpException('No url or model found for request [' . $slug . '] for locale [' . $locale . '].');
    }

    private static function createRedirect(string $url): RedirectResponse
    {
        return new RedirectResponse($url, 301, []);
    }
}
