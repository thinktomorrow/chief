<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Concerns\HasTags;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
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

    /**
     * Every field is rendered in a formgroup container view,
     * this view takes care of the localization of the field.
     */
    protected string $formgroupView = 'chief-forms::components.formgroup.index';
    protected string $formgroupWindowView = 'chief-forms::components.formgroup-window.index';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->id($key);
        $this->name($key);
        $this->columnName($key);
    }

    public static function make(string $key)
    {
        return new static($key);
    }

    public function render(): View
    {
        $view = $this->displayInWindow
            ? $this->formgroupWindowView
            : $this->formgroupView;

        return view($view, array_merge($this->data(), [
            'component' => $this,
        ]));
    }

    public function fill(Manager $manager, Model $model): void
    {
        //
    }
}
