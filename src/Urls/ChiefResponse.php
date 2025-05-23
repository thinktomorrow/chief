<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls;

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
use Thinktomorrow\Chief\Fragments\ActiveContextId;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;
use Throwable;

final class ChiefResponse
{
    public static function fromSlug(string $slug, ?string $locale = null): BaseResponse
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        try {
            $slug = Str::ascii($slug);

            $urlRecord = UrlRecord::findBySlug($slug, $locale);

            if ($urlRecord->isRedirect()) {
                return self::createRedirect(
                    self::findModel($urlRecord->getRedirectTo())->url($locale)
                );
            }

            return self::createResponse($urlRecord);

        } catch (Throwable $e) {
            if (config('chief.strict') || ! self::shouldBeIgnored($e)) {
                throw $e;
            }
        }

        throw new NotFoundHttpException('No url or model found for request ['.$slug.'] for locale ['.$locale.'].');
    }

    private static function createRedirect(string $url): RedirectResponse
    {
        return new RedirectResponse($url, 301, []);
    }

    private static function createResponse(UrlRecord $urlRecord): BaseResponse
    {
        if (! $urlRecord->isVisitable()) {
            throw new NotFoundHttpException('Url ['.$urlRecord->slug.'] is offline.');
        }

        $model = self::findModel($urlRecord);

        ActiveContextId::setForSite($urlRecord->site, $model);

        return $model->response();
    }

    private static function findModel(UrlRecord $urlRecord): Visitable
    {
        $model = ModelReference::make($urlRecord->model_type, $urlRecord->model_id)->instance();

        if (! $model->isVisitable()) {
            throw new NotFoundHttpException('Model found for request ['.$urlRecord->slug.'] but it is not visitable.');
        }

        return $model;
    }

    private static function shouldBeIgnored(Throwable $e): bool
    {
        return ! is_null(Arr::first(self::ignoredExceptions(), fn ($type) => $e instanceof $type));
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
}
