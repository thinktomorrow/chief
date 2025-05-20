<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;

/**
 * @property int $id
 * @property string $slug
 * @property string|null $site
 * @property string $model_type
 * @property int $model_id
 * @property int|null $redirect_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class UrlRecord extends Model
{
    public $table = 'chief_urls';

    public $guarded = [];

    /**
     * Find matching url record for passed slug and site.
     *
     * @throws UrlRecordNotFound
     */
    public static function findBySlug(string $slug, string $site): UrlRecord
    {
        // Clear the input from any trailing slashes.
        if ($slug != '/') {
            $slug = trim($slug, '/');
        }

        $record = static::where('slug', $slug)
            ->where('site', $site)
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if (! $record) {
            throw new UrlRecordNotFound('No url record found by slug ['.$slug.'] for site ['.$site.'].');
        }

        return $record;
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model')->withoutGlobalScopes();
    }

    /**
     * Find matching url record for passed slug and site.
     *
     * @throws UrlRecordNotFound
     */
    public static function findByModel(Model $model, string $locale): UrlRecord
    {
        $record = static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('site', $locale)
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if (! $record) {
            throw new UrlRecordNotFound('No url record found for model ['.$model->getMorphClass().'@'.$model->id.'] for site ['.$locale.'].');
        }

        return $record;
    }

    public static function findSlugByModel(Model $model, string $locale): ?string
    {
        try {
            $currentSlug = static::findByModel($model, $locale)->slug;
        } catch (UrlRecordNotFound $e) {
            $currentSlug = '';
        }

        return $currentSlug;
    }

    public static function getByModel(Model $model): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->get();
    }

    public static function existForModel(Model $model): bool
    {
        return static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->exists();
    }

    public function isRedirect(): bool
    {
        return (bool) ($this->redirect_id);
    }

    public function getRedirectTo(): ?self
    {
        return static::find($this->redirect_id);
    }

    public function isHomepage(): bool
    {
        return HomepageSlug::is($this->slug);
    }

    public static function existsIgnoringRedirects(?string $slug, ?string $locale = null, ?Model $ignoredModel = null): bool
    {
        return static::exists($slug, $locale, $ignoredModel, false);
    }

    public static function exists(?string $slug, ?string $locale = null, ?Model $ignoredModel = null, bool $includeRedirects = true): bool
    {
        $builder = static::where('slug', $slug);

        if ($locale) {
            $builder->where('site', $locale);
        }

        if (! $includeRedirects) {
            $builder->whereNull('redirect_id');
        }

        if ($ignoredModel) {
            $builder->whereNotIn('id', function ($query) use ($ignoredModel) {
                $query->select('id')
                    ->from('chief_urls')
                    ->where('model_type', '=', $ignoredModel->getMorphClass())
                    ->where('model_id', '=', $ignoredModel->id);
            });
        }

        return $builder->count() > 0;
    }

    public static function allOnlineModels(string $locale): Collection
    {
        $records = static::where('site', $locale)
            ->where('redirect_id', '=', null)
            ->where('status', '=', LinkStatus::online->value)
            ->orderBy('updated_at', 'DESC')
            ->get();

        // Because of archived pages the mapping can be null so we reject them before we check for online
        return $records->map(function (UrlRecord $urlRecord) {
            return Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);
        })->reject(function ($model) {
            return $model == null;
        })->reject(function (Visitable $model) {
            return ! $model->isVisitable();
        });
    }

    public function changeOwningModel(Model $model): void
    {
        $this->model_type = $model->getMorphClass();
        $this->model_id = $model->id;
    }

    public function isVisitable(): bool
    {
        if ($this->isOffline()) {
            // When admin is logged in and this request is in preview mode, we allow the url to be viewed
            return PreviewMode::fromRequest()->check();
        }

        return true;
    }

    public function isOffline(): bool
    {
        return $this->status === LinkStatus::offline->value;
    }

    public function changeStatus(LinkStatus $status): void
    {
        $this->status = $status->value;
    }
}
