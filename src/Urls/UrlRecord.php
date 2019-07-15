<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\FlatReferences\FlatReferencePresenter;

class UrlRecord extends Model
{
    public $table = 'chief_urls';

    public $guarded = [];

    /**
     * Find matching url record for passed slug and locale. The locale parameter will try
     * to match specific given locales first and records without locale as fallback.
     *
     * @param string $slug
     * @param string $locale
     * @return UrlRecord
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

        if (!$record) {
            throw new UrlRecordNotFound('No url record found by slug ['.$slug.'] for locale ['.$locale.'].');
        }

        return $record;
    }

    /**
     * Find matching url record for passed slug and locale. The locale parameter will try
     * to match specific given locales first and records without locale as fallback.
     *
     * @param Model $model
     * @param string $locale
     * @return UrlRecord
     * @throws UrlRecordNotFound
     */
    public static function findByModel(Model $model, string $locale): UrlRecord
    {
        $record = static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('locale', $locale)
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if (!$record) {
            throw new UrlRecordNotFound('No url record found for model ['.$model->getMorphClass().'@'.$model->id.'] for locale ['.$locale.'].');
        }

        return $record;
    }

    public static function getByModel(Model $model)
    {
        return static::where('model_type', $model->getMorphClass())
                     ->where('model_id', $model->id)
                     ->get();
    }

    public function replaceAndRedirect(array $values): UrlRecord
    {
        $newRecord = static::create(array_merge([
            'locale'              => $this->locale,
            'model_type'          => $this->model_type,
            'model_id'            => $this->model_id,
        ], $values));

        $this->redirectTo($newRecord);

        return $newRecord;
    }

    public function redirectTo(self $record = null): ?UrlRecord
    {
        if (!$record) {
            return $this->isRedirect() ? static::find($this->redirect_id) : null;
        }

        $this->redirect_id = $record->id;
        $this->save();

        return null;
    }

    public function isRedirect(): bool
    {
        return !!($this->redirect_id);
    }

    public static function existsIgnoringRedirects($slug, string $locale = null, Model $ignoredModel = null): bool
    {
        return static::exists($slug, $locale, $ignoredModel, false);
    }

    public static function exists($slug, string $locale = null, Model $ignoredModel = null, bool $includeRedirects = true): bool
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

        return ($builder->count() > 0);
    }

    public static function allOnlineModels(): array
    {
        return chiefMemoize('all-online-models', function () {

            $liveUrlRecords = static::whereNull('redirect_id')->select('model_type', 'model_id')->groupBy('model_type', 'model_id')->get()->mapToGroups(function($record) {
                return [$record->model_type => $record->model_id];
            });

            // Get model for each of these records...
            $models = $liveUrlRecords->map(function($record, $key){
                return Morphables::instance($key)->find($record->toArray());
            })->each->reject(function ($model) {
                // Invalid references to archived or removed models where url record still exists.
                return is_null($model);
            })->flatten();

            return FlatReferencePresenter::toGroupedSelectValues($models)->toArray();
        });
    }
}
