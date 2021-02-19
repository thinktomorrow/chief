<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Morphable;

trait Morphable
{
    use EloquentMorphableInstantiation;
    use EloquentMorphableCreation;

    public function morphKey(): ?string
    {
        /**
         * In case of a hydrated instance, we will take the key from database since in many cases the data
         * is correct but hydrated into a generic Page class and not yet the expected class
         * so we want to use the proper morph identification at those moments
         */
        if (isset($this->morph_key) && $this->morph_key) {
            return $this->morph_key;
        }

        // Get static class or mapped class if set so in laravel app.
        return $this->getMorphClass();
    }

    /**
     * Retrieve results for one specific morphable model.
     *
     * @param $query
     * @param string|null $morphkey
     * @return mixed
     */
    public function scopeMorphable($query, string $morphkey = null)
    {
        return $query->withoutGlobalScope(static::globalMorphableScope())
            ->where('morph_key', '=', $morphkey);
    }

    /**
     * Ignore the morphable scoping. This will fetch all results,
     * regardless of the specific morphable models.
     */
    public static function ignoreMorphables()
    {
        return self::withoutGlobalScope(static::globalMorphableScope());
    }

    protected static function bootMorphable()
    {
        static::addGlobalScope(static::globalMorphableScope());
    }

    protected static function globalMorphableScope()
    {
        return new GlobalMorphableScope();
    }

    protected function initializeMorphable()
    {
        if (! isset($this->morph_key)) {
            $this->morph_key = $this->morphKey();
        }
    }
}
