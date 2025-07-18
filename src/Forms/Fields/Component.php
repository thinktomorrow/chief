<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Forms\Concerns\HasComponentRendering;
use Thinktomorrow\Chief\Forms\Concerns\HasCustomAttributes;
use Thinktomorrow\Chief\Forms\Concerns\HasDescription;
use Thinktomorrow\Chief\Forms\Concerns\HasElementId;
use Thinktomorrow\Chief\Forms\Concerns\HasTitle;
use Thinktomorrow\Chief\Forms\Concerns\HasView;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasAutofocus;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasColumnName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCustomFillForSaving;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasCustomPrepForSaving;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasDefault;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasFieldToggle;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasId;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasKey;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasLabel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasModel;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasName;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasPlaceholder;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasSave;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValidation;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasWireModelType;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameDefaults;
use Thinktomorrow\Chief\Forms\Fields\Locales\HasLocalizableProperties;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedFieldDefaults;
use Thinktomorrow\Chief\Forms\Tags\WithTags;
use Thinktomorrow\Chief\Forms\UI\Livewire\WithWireableFieldDefaults;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Sites\ChiefSites;

abstract class Component extends \Illuminate\View\Component implements Htmlable, Wireable
{
    use FieldNameDefaults;
    use HasAutofocus;
    use HasColumnName;
    use HasComponentRendering;
    use HasCustomAttributes;
    use HasCustomFillForSaving;
    use HasCustomPrepForSaving;
    use HasDefault;
    use HasDescription;
    use HasElementId;
    use HasFieldToggle;
    use HasId;

    // Field concerns
    use HasKey;
    use HasLabel;

    // Generic component concerns
    use HasLocalizableProperties;
    use HasModel;
    use HasName;
    use HasPlaceholder;
    use HasSave;
    use HasTitle;
    use HasValidation;
    use HasValue;
    use HasView;
    use HasWireModelType;
    use LocalizedFieldDefaults;
    use WithTags;
    use WithWireableFieldDefaults;

    /**
     * Every field is rendered in a formgroup container view,
     * this view takes care of the localization of the field.
     */
    protected string $fieldTemplate = 'chief-form::templates.field';

    protected string $fieldPreviewTemplate = 'chief-form::templates.field-preview';

    public function __construct(string $key)
    {
        $this->key($key);
        $this->id($key);
        $this->name($key);
        $this->columnName($key);

        // This causes livewire to refresh field DOM...
        $this->elementId($key.'_'.Str::random());
        //        $this->elementId($key);
    }

    public static function make(string $key)
    {
        return new static($key);
    }

    public function render(): View
    {
        return view($this->fieldTemplate, array_merge($this->data(), [
            'component' => $this,
            'field' => $this, // Convenience for field templates
        ]));
    }

    public function renderPreview(): View
    {
        return view($this->fieldPreviewTemplate, array_merge($this->data(), [
            'component' => $this,
            'field' => $this, // Convenience for field templates
        ]));
    }

    public function fill(Manager $manager, Model $model): void
    {
        // Override this method so you can fill in a component with custom endpoint routes.
        // This is an empty fill and acts as the component default.
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Shows in the form that the field is localized.
     * Displays an small icon next to the label
     */
    public function showsLocaleIndicatorInForm(): bool
    {
        return $this->hasLocales() && count(ChiefSites::locales()) > 1;
        //        return $this->hasLocales() && count($this->getLocales()) > 1;
    }

    protected function wireableMethods(array $components): array
    {
        return [
            ...(isset($this->id) ? ['id' => $this->id] : []),
            ...(isset($this->components) ? ['components' => $components] : []),
            ...(isset($this->elementId) ? ['elementId' => $this->elementId] : []),
            ...(isset($this->name) ? ['name' => $this->name] : []),
            ...(isset($this->columnName) ? ['columnName' => $this->columnName] : []),
            ...(isset($this->elementId) ? ['elementId' => $this->elementId] : []),
            ...(isset($this->locales) ? ['locales' => $this->locales] : []),
            ...(isset($this->scopedLocale) ? ['setScopedLocale' => $this->scopedLocale] : []),
            ...(isset($this->fieldNameTemplate) ? ['setFieldNameTemplate' => $this->fieldNameTemplate] : []),
            ...(isset($this->label) ? ['label' => $this->label] : []),
            ...(isset($this->default) ? ['default' => $this->default] : []),
            ...(isset($this->description) ? ['description' => $this->description] : []),
            ...(isset($this->options) ? ['options' => $this->options] : []),
            ...(isset($this->optionsAreAssumedGrouped) ? ['hasOptionGroups' => null] : []),
            ...(isset($this->allowMultiple) ? ['multiple' => $this->allowMultiple] : []),
            ...(isset($this->placeholder) ? ['placeholder' => $this->placeholder] : []),
            ...(isset($this->autofocus) ? ['autofocus' => $this->autofocus] : []),
            ...(isset($this->isRequired) && $this->isRequired ? ['required' => true] : []),
            ...(isset($this->rules) ? ['rules' => $this->rules] : []),
            ...(isset($this->validationMessages) ? ['validationMessages' => $this->validationMessages] : []),
            ...(isset($this->validationAttribute) ? ['validationAttribute' => $this->validationAttribute] : []),
            ...(isset($this->fieldToggles) ? ['toggleFields' => $this->fieldToggles] : []),
            ...(isset($this->characterCount) ? ['characterCount' => $this->characterCount] : []),
            ...(isset($this->redactorOptions) ? ['redactorOptions' => $this->redactorOptions] : []),
            ...(isset($this->customAttributes) ? ['customAttributes' => $this->customAttributes] : []),
            ...(isset($this->view) ? ['setView' => $this->view] : []),
            ...(isset($this->previewView) ? ['previewView' => $this->previewView] : []),
            ...(isset($this->useValueFallback) ? ['useValueFallback' => $this->useValueFallback] : []),
            ...(isset($this->showAsToggle) ? ['showAsToggle' => $this->showAsToggle] : []),
            ...(isset($this->wireModelType) ? ['wireModelType' => $this->wireModelType] : []),
            ...(isset($this->prepend) ? ['prepend' => $this->prepend] : []),
            ...(isset($this->append) ? ['append' => $this->append] : []),
            ...(isset($this->hivePrompts) || (isset($this->hiveEnabled) && $this->hiveEnabled) ? ['hive' => $this->hivePrompts] : []),
        ];
    }
}
