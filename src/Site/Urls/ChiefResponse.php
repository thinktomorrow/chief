<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Throwable;

final class ChiefResponse
{
    public static function fromSlug(string $slug, $locale = null): BaseResponse
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        try {
            $slug = Str::ascii($slug);

            $urlRecord = UrlRecord::findBySlug($slug, $locale);

            if ($urlRecord->isRedirect()) {
                return static::createRedirect(
                    static::findModel($urlRecord->redirectTo())->url($locale)
                );
            }

            return static::findModel($urlRecord)->response();
        } catch (\Throwable $e) {
            if (config('chief.strict') || ! static::shouldBeIgnored($e)) {
                throw $e;
            }
        }

        throw new NotFoundHttpException('No url or model found for request [' . $slug . '] for locale [' . $locale . '].');
    }

    private static function shouldBeIgnored(Throwable $e): bool
    {
        return ! is_null(Arr::first(static::ignoredExceptions(), fn ($type) => $e instanceof $type));
    }

    private static function ignoredExceptions(): array
    {
        return [
            UrlRecordNotFound::class,
            AuthenticationException::class,
            AuthorizationException::class,
            HttpException::class,
            HttpResponseException::class,
            ModelNotFoundException::class,
        ];
    }

    private static function findModel(UrlRecord $urlRecord): Visitable
    {
        $model = ModelReference::make($urlRecord->model_type, $urlRecord->model_id)->instance();

        if (! $model->isVisitable()) {
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
