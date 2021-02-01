<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Assistants;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

trait ManagedModelDefaults
{
    use SavingFields;

    public static function managedModelKey(): string
    {
        $shortClassName = (new \ReflectionClass(static::class))->getShortName();

        return Str::snake(Str::singular($shortClassName));
    }

    public function adminLabel(string $key, $default = null, array $replace = [])
    {
        if (! ($value = data_get($this->adminLabels(), $key)) ) {
            return $default;
        }

        return (is_string($value) && !empty($replace)) ? sprintf($value, $replace) : $value;
    }

    /**
     * Default set of admin labels.
     * This can be set per manager for customisation.
     *
     * @return array
     */
    private function adminLabels(): array
    {
        $singular = Str::of(static::managedModelKey())->singular()->replace('_',' ')->__toString();

        return [
            /**
             * Generic labels
             * these values are used when no model instance is expected or available yet.
             * They represent a model class value and not of an existing instance.
             */
            'label' => $singular, // Label used to refer to the generic model
            'nav_label' => $singular, // label used in the chief navigation
            'page_title' => Str::of(static::managedModelKey())->plural()->replace('_',' ')->__toString(), // Generic collection title, for example used on index

            /**
             * Instance labels
             * These values are used in context of an existing record.
             * They usually point to a specific model attribute.
             */
            'title' => $this->title ?? $singular, // Title of an existing model, as used on the index card and edit header
            'card' => [
                'subtitle' => '', // used in the index card below the title
                'online_status' => $this->onlineStatusAsLabel(), // Used to indicate the online status of this model
            ],
        ];
    }

    private function onlineStatusAsLabel(): string
    {
        if(!$this instanceof StatefulContract) return '';

        if ($this->stateOf(PageState::KEY) === PageState::PUBLISHED) {
            return $this instanceof ProvidesUrl
                ? '<a href="' . $this->url() . '" target="_blank"><em>online</em></a>'
                : '<span><em>online</em></span>';
        }

        if ($this->stateOf(PageState::KEY) === PageState::DRAFT) {
            return $this instanceof ProvidesUrl
                ? '<a href="' . $this->url() . '" target="_blank" class="text-error"><em>offline</em></a>'
                : '<span class="text-error"><em>offline</em></span>';
        }

        if ($this->stateOf(PageState::KEY) === PageState::ARCHIVED) {
            return '<span><em>gearchiveerd</em></span>';
        }

        return '';
    }
}
