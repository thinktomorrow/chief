<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Tests\Fakes;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActingAsParent;
use Thinktomorrow\Chief\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ResolvingRoute;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\DynamicAttributes\HasDynamicAttributes;

class CustomTableArticleFake extends Model implements ManagedModel, HasAsset, ActsAsChild, ViewableContract, StatefulContract, ActsAsParent, ProvidesUrl
{
    use HasDynamicAttributes;
    use AssetTrait;
    use ActingAsChild;
    use Viewable;
    use ResolvingRoute;
    use ActingAsParent;

    public $translatedAttributes = ['question','title','slug'];
    protected static $baseUrlSegment = '/';

    protected static $managedModelKey = 'customArticles';

    public $table = 'custom_articles';

    public static function migrateUp()
    {
        Schema::create('custom_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

    }

    public static function managedModelKey(): string
    {
        if (isset(static::$managedModelKey)) {
            return static::$managedModelKey;
        }

        throw new \Exception('Missing required static property \'managedModelKey\' on ' . static::class . '.');
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    public function flatReferenceLabel(): string
    {
        if ($this->exists) {
            $status = !$this->isPublished() ? ' [' . $this->statusAsPlainLabel() . ']' : null;

            return $this->title ? $this->title . $status : '';
        }

        return '';
    }

    public function flatReferenceGroup(): string
    {
        $classKey = get_class($this);
        if (property_exists($this, 'labelSingular')) {
            $labelSingular = $this->labelSingular;
        } else {
            $labelSingular = Str::singular($classKey);
        }

        return $labelSingular;
    }

    /** @inheritdoc */
    public function url(string $locale = null): string
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }
        try {
            $slug = MemoizedUrlRecord::findByModel($this, $locale)->slug;

            return $this->resolveUrl($locale, [$slug]);
        } catch (UrlRecordNotFound $e) {
            return '';
        }
    }

    public function resolveUrl(string $locale = null, $parameters = null): string
    {
        $routeName = config('thinktomorrow.chief.route.name');

        return $this->resolveRoute($routeName, $parameters, $locale);
    }

    /** @inheritdoc */
    public function previewUrl(string $locale = null): string
    {
        return $this->url($locale) . '?preview-mode';
    }


    /** @inheritdoc */
    public function baseUrlSegment(string $locale = null): string
    {
        if (!isset(static::$baseUrlSegment)) {
            return '/';
        }

        if (!is_array(static::$baseUrlSegment)) {
            return static::$baseUrlSegment;
        }

        // When an array, we try to locate the expected segment by locale
        $key = $locale ?? app()->getlocale();

        if (isset(static::$baseUrlSegment[$key])) {
            return static::$baseUrlSegment[$key];
        }

        $fallback_locale = config('app.fallback_locale');
        if (isset(static::$baseUrlSegment[$fallback_locale])) {
            return static::$baseUrlSegment[$fallback_locale];
        }

        // Fall back to first entry in case no match is found
        return reset(static::$baseUrlSegment);
    }


    public function stateOf($key): string
    {
        return $this->$key ?? PageState::DRAFT;
    }

    public function changeStateOf($key, $state)
    {
        // Ignore change to current state - it should not trigger events either
        if ($state === $this->stateOf($key)) {
            return;
        }

        PageState::assertNewState($this, $key, $state);

        $this->$key = $state;
    }
}
