<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Database\Eloquent\Model;

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

    public static function findRecentRedirect(Model $model, string $locale): ?self
    {
        return static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where('locale', $locale)
            ->where('redirect_id', '<>', null)
            ->orderBy('redirect_id', 'ASC')
            ->first();
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

    // Remove all urls that came after this one
    public function revert()
    {
        if (!$this->isRedirect()) {
            return;
        }

        if ($record = static::where('id', $this->redirect_id)->first()) {
            $record->revert();
            $record->delete();
        }

        $this->redirect_id = null;
        $this->save();
    }

    public function isRedirect(): bool
    {
        return !!($this->redirect_id);
    }

    public function isHomepage(): bool
    {
        return $this->slug === '/';
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
}
