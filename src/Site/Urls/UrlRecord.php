<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class UrlRecord extends Model
{
    public $table = 'chief_urls';

    public $guarded = [];

    /**
     * Find matching url record for passed slug and locale. The locale parameter will try
     * to match specific given locales first and records without locale as fallback.
     *
     * @throws UrlRecordNotFound
     */
    public static function findBySlug(string $slug, string $locale): UrlRecord
    {
        // Clear the input from any trailing slashes.
        if ($slug != '/') {
            $slug = trim($slug, '/');
        }

        $record = static::where('slug', $slug)
            ->where('locale', $locale)
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if (! $record) {
            throw new UrlRecordNotFound('No url record found by slug ['.$slug.'] for locale ['.$locale.'].');
        }

        return $record;
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Find matching url record for passed slug and locale. The locale parameter will try
     * to match specific given locales first and records without locale as fallback.
     *
     * @throws UrlRecordNotFound
     */
    public static function findByModel(Model $model, string $locale): UrlRecord
    {
        $record = static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('locale', $locale)
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if (! $record) {
            throw new UrlRecordNotFound('No url record found for model ['.$model->getMorphClass().'@'.$model->id.'] for locale ['.$locale.'].');
        }

        return $record;
    }

    public static function findSlugByModel(Model $model, ?string $locale = null): ?string
    {
        try {
            $currentSlug = static::findByModel($model, $locale ?? app()->getLocale())->slug;
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

    public static function findRecentRedirect(Model $model, string $locale): ?self
    {
        return static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('locale', $locale)
            ->where('redirect_id', '<>', null)
            ->orderBy('updated_at', 'DESC')
            ->first();
    }

    public function replaceAndRedirect(string $slug): UrlRecord
    {
        $newRecord = static::firstOrCreate([
            'locale' => $this->locale,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'slug' => $slug,
        ]);

        $this->redirectTo($newRecord);

        return $newRecord;
    }

    public function redirectTo(?self $record = null): ?UrlRecord
    {
        if (! $record) {
            return $this->isRedirect() ? static::find($this->redirect_id) : null;
        }

        if ($record->id === $this->id) {
            throw new \InvalidArgumentException('Cannot redirect to itself. Failed to create a redirect from ['.$this->slug.'] to ['.$record->slug.']');
        }

        $this->redirect_id = $record->id;
        $this->save();

        return null;
    }

    // Remove all urls that came after this one

    /**
     * @return void
     */
    public function revert()
    {
        if (! $this->isRedirect()) {
            return;
        }

        // Remove this redirect relation so it's no longer cascading when main url is getting deleted.
        $redirect_id = $this->redirect_id;
        $this->redirect_id = null;
        $this->save();

        if ($record = static::where('id', $redirect_id)->first()) {
            $record->revert();
            $record->delete();
        }
    }

    public function isRedirect(): bool
    {
        return (bool) ($this->redirect_id);
    }

    public function isHomepage(): bool
    {
        return $this->slug === '/';
    }

    public static function existsIgnoringRedirects(?string $slug, ?string $locale = null, ?Model $ignoredModel = null): bool
    {
        return static::exists($slug, $locale, $ignoredModel, false);
    }

    public static function exists(?string $slug, ?string $locale = null, ?Model $ignoredModel = null, bool $includeRedirects = true): bool
    {
        $builder = static::where('slug', $slug);

        if ($locale) {
            $builder->where('locale', $locale);
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
        $records = static::where('locale', $locale)
            ->where('redirect_id', '=', null)
            ->orderBy('updated_at', 'DESC')
            ->get();

        // Filter out offline urls... TODO: this should be a state that is owned by the url and not the model.
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
}
