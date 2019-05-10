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
        $record = static::where('slug', trim($slug,'/'))
                        ->where(function($query) use($locale){
                            $query->where('locale', $locale)
                                  ->orWhereNull('locale');
                        })

                        // Make sure that the generic non-locale record is fetched after the locale one.
                        ->orderBy('locale', 'DESC')
                        ->orderBy('redirect_id', 'ASC')
                        ->first();

        if(!$record){
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
    public static function findByModel(Model $model, string $locale = null): UrlRecord
    {
        $record = static::where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->where(function($query) use($locale){
                $query->where('locale', $locale)
                    ->orWhereNull('locale');
            })

            // Make sure that the generic non-locale record is fetched after the locale one.
            ->orderBy('locale', 'DESC')
            ->orderBy('redirect_id', 'ASC')
            ->first();

        if(!$record){
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

    public function replace(array $values): UrlRecord
    {
        $newRecord = static::create(array_merge([
            'locale'     => $this->locale,
            'model_type' => $this->model_type,
            'model_id'   => $this->model_id,
        ], $values));

        $this->redirectTo($newRecord);

        return $newRecord;
    }

    public function redirectTo(self $record = null): ?UrlRecord
    {
        if(!$record){
            return $this->isRedirect() ? static::find($this->redirect_id) : null;
        }

        $this->redirect_id = $record->id;
        $this->save();

        return null;
    }

    /**
     * Is this an url that is applicable to all locales?
     * @return bool
     */
    public function isWildCard(): bool
    {
        return $this->locale == null;
    }

    public function isRedirect(): bool
    {
        return !!($this->redirect_id);
    }

    public static function exists($locale, $slug, Model $ignoredModel = null): bool
    {
        $builder = static::where('locale', $locale)
                        ->where('slug', $slug);

        if($ignoredModel){
            $builder->whereNotIn('id', function($query) use($ignoredModel){
                $query->select('id')
                      ->from('chief_urls')
                      ->where('model_type', '=', $ignoredModel->getMorphClass())
                      ->where('model_id', '=', $ignoredModel->id);
            });
        }

        return ($builder->count() > 0);
    }
}