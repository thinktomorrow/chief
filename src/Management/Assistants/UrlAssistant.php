<?php

namespace Thinktomorrow\Chief\Management\Assistants;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Manager;

class UrlAssistant implements Assistant
{
    private $manager;

    private $model;

    public function manager(Manager $manager)
    {
        $this->manager  = $manager;
        $this->model    = $manager->model();
    }

    public static function key(): string
    {
        return 'url';
    }

    public function route($verb): ?string
    {
        // store new url
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('slug')
                ->translatable($this->model->availableLocales())
                ->validation($this->model->id
                    ? 'required-fallback-locale|unique:page_translations,slug,' . $this->model->id . ',page_id'
                    : 'required-fallback-locale|unique:page_translations,slug'
                    ,[], [
                        'trans.'.config('app.fallback_locale', 'nl').'.slug' => 'slug'
                    ])
                ->label('Link')
                ->description('De unieke url verwijzing naar deze pagina.')
                ->prepend(collect($this->model->availableLocales())->mapWithKeys(function ($locale) {
                    return [$locale => url($this->model->baseUrlSegment($locale)).'/'];
                })->all()),
        ]);
    }

    public function can($verb): bool
    {
        return true;
    }

    public function guard($verb): Assistant
    {
        return $this;
    }
}