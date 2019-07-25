<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls\Fakes;

use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Concerns\Archivable\Archivable;
use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class ProductFake extends ManagedModelFakeFirst implements ProvidesUrl, ProvidesFlatReference
{
    use Archivable;

    protected static $managedModelKey = 'products';

    public $translationModel = ManagedModelFakeTranslation::class;
    public $translationForeignKey = 'managed_model_fake_id';

    public function url(string $locale = null): string
    {
        if(!$locale) $locale = app()->getLocale();

        return UrlRecord::findByModel($this, $locale)->slug;
    }

    public function previewUrl(string $locale = null): string
    {
        return $this->url($locale);
    }

    public static function baseUrlSegment(string $locale = null): string
    {
        return '/';
    }

    /**
     * Create the full url for this given resource
     *
     * @param string|null $locale
     * @param array|string|null $parameters
     * @return string
     */
    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('thinktomorrow.chief.route.name');

        return route($routeName, $parameters);
    }

    /**
     * Composite key consisting of the type of class combined with the
     * model id. Both are joined with an @ symbol. This is used as
     * identifier of the instance mostly in selections.
     */
    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    /**
     * Label that identifies the flat reference with a human readable string.
     * This is mostly used in the interface of the admin panel.
     *
     * @return string
     */
    public function flatReferenceLabel(): string
    {
        return $this->id;
    }

    /**
     * Label that identifies the grouping under which this reference should belong.
     * This is a categorization used to group select options and other listings.
     * It also combines similar models together in the view rendering.
     *
     * @return string
     */
    public function flatReferenceGroup(): string
    {
        return 'product-fakes';
    }
}