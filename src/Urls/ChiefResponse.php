<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Concerns\Morphable\NotFoundMorphKey;
use Thinktomorrow\Chief\States\Publishable\PreviewMode;

class ChiefResponse extends Response
{
    final public function __construct($content = '', int $status = 200, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }

    public static function fromSlug(string $slug, $locale = null)
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        try {
            $urlRecord = UrlRecord::findBySlug($slug, $locale);

            $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

            if ($urlRecord->isRedirect()) {

                // If model is not found, it probably means it is archived or removed
                // So we detect the model based on the redirect target url.
                if (!$model) {
                    $targetUrlRecord = $urlRecord->redirectTo();

                    $targetModel = Morphables::instance($targetUrlRecord->model_type)->find($targetUrlRecord->model_id);

                    if (!$targetModel) {
                        throw new \DomainException('Corrupt target model for this url request. Model by reference [' . $targetUrlRecord->model_type . '@' . $targetUrlRecord->model_id . '] has probably been archived or deleted.');
                    }

                    return static::createRedirect($targetModel->url($locale));
                }

                return static::createRedirect($model->url($locale));
            }

            if (!$model) {
                throw new \DomainException('Corrupt target model for this url request. Model by reference [' . $urlRecord->model_type . '@' . $urlRecord->model_id . '] has probably been archived or deleted.');
            }

            if (method_exists($model, 'isPublished') && !$model->isPublished()) {

                /** When admin is logged in and this request is in preview mode, we allow the view */
                if (!PreviewMode::fromRequest()->check()) {
                    throw new NotFoundHttpException('Model found for request [' . $slug . '] but it is not published.');
                }
            }

            return new static($model->renderView(), 200);
        } catch (UrlRecordNotFound | NotFoundMorphKey $e) {
            if (config('thinktomorrow.chief.strict')) {
                throw $e;
            }
        }

        throw new NotFoundHttpException('No url or model found for request [' . $slug . '] for locale [' . $locale . '].');
    }

    private static function createRedirect(string $url)
    {
        return new RedirectResponse($url, 301, []);
    }
}
