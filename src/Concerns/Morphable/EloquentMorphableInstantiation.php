<?php


namespace Thinktomorrow\Chief\Concerns\Morphable;

use Illuminate\Database\Eloquent\Model;

/**
 * Overrides the eloquent model instantiation to account for proper morphable object creation.
 * This makes sure that the fetched or created class is a instance of the morphable type.
 */
trait EloquentMorphableInstantiation
{

    /**
     * Custom build for new Collections where we convert any models to the correct collection types.
     * Magic override warning.
     *
     * @ref \Illuminate\Database\Eloquent\Model::newCollection()
     *
     * @param array $models
     * @return
     * @throws NotFoundMorphKey
     */
    public function newCollection(array $models = [])
    {
        foreach ($models as $k => $model) {
            if ($model instanceof MorphableContract && $morphKey = $model->morphKey()) {
                $models[$k] = $this->convertToMorphInstance($model, $morphKey);
            }
        }

        return parent::newCollection($models);
    }

    /**
     * Clone the model into its expected collection class
     * @ref \Illuminate\Database\Eloquent\Model::replicate()
     *
     * @param Model $model
     * @param string $morphKey
     * @return Model
     * @throws NotFoundMorphKey
     */
    private function convertToMorphInstance(Model $model, string $morphKey): Model
    {
        // Here we load up the proper collection model instead of the generic base class.
        return tap(Morphables::instance($morphKey, $model->attributes), function ($instance) use ($model) {
            $instance->setRawAttributes($model->attributes);
            $instance->setRelations($model->relations);
            $instance->exists = $model->exists;

            $this->loadCustomTranslations($instance);
        });
    }

    /**
     * When eager loading the translations via the with attribute, they are loaded every time.
     * Here we eager load the proper translations if they are set on a different model than the original one.
     * The current loaded translations are empty because of they tried matching with the original table.
     *
     * @param $instance
     */
    private function loadCustomTranslations($instance)
    {
        if ($this->requiresCustomTranslation($instance)) {
            if (!is_array($instance->with) || !in_array('translations', $instance->with)) {
                $instance->unsetRelation('translations');
            } else {
                // TODO: this is a heavy queryload since it loads new translations for each model...
                // Removing this could reduce load by approx. 50%.
                // $instance->load('translations');
            }
        }
    }

    /**
     * @param $instance
     * @return bool
     */
    private function requiresCustomTranslation(Model $instance): bool
    {
        // TODO: this check should not be explicitly targeted at PageTranslation ...
        return $instance->relationLoaded('translations') && $instance->translations->isEmpty() && $instance->translationModel != PageTranslation::class;
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool $exists
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        if (!isset($attributes['morph_key'])) {
            return parent::newInstance($attributes, $exists);
        }

        $model = Morphables::instance($attributes['morph_key'], (array)$attributes);

        $model->exists = $exists;

        $model->setConnection(
            $this->getConnectionName()
        );

        return $model;
    }
}
