<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasTags;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAutofocus;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasColumnName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasFieldToggle;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocales;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModelValuePreparation;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPlaceholder;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasSave;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValidation;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Managers\Manager;

abstract class Component extends \Illuminate\View\Component implements Htmlable
{
    // Generic component concerns
    use HasLocalizableProperties;
    use HasComponentRendering;
    use HasModel;
    use HasView;
    use HasComponents;
    use HasCustomAttributes;
    use HasTags;
    use HasFieldToggle;

    // Field concerns
    use HasKey;
    use HasName;
    use HasLabel;
    use HasDescription;
    use HasColumnName;
    use HasId;
    use HasValue;
    use HasLocales;
    use HasDefault;
    use HasPlaceholder;
    use HasAutofocus;
    use HasTitle;
    use HasValidation;
    use HasModelValuePreparation;
    use HasSave;
    use HasElementId;

    /**
     * Every field is rendered in a formgroup container view,
     * this view takes care of the localization of the field.
     */
    protected string $fieldFormView = 'chief-form::templates.field-in-form';
    protected string $fieldWindowView = 'chief-form::templates.field-in-window';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->id($key);
        $this->name($key);
        $this->columnName($key);

        $this->elementId($key.'_'.Str::random());
    }

    public static function make(string $key)
    {
        return new static($key);
    }

    public function render(): View
    {
        $view = $this->editInSidebar
            ? $this->fieldWindowView
            : $this->fieldFormView;

        return view($view, array_merge($this->data(), [
            'component' => $this,
        ]));
    }

    public function fill(Manager $manager, Model $model): void
    {
        // Override this method so you can fill in a component with custom endpoint routes.
        // This is an empty fill and acts as the component default.
    }

    public function fieldFormView(string $fieldFormView): static
    {
        $this->fieldFormView = $fieldFormView;

        return $this;
    }

    public function fieldWindowView(string $fieldWindowView): static
    {
        $this->fieldWindowView = $fieldWindowView;

        return $this;
    }
}
