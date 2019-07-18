<?php

namespace Thinktomorrow\Chief\Management\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\Urls\MemoizedUrlRecord;
use Thinktomorrow\Chief\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlSlugFields;
use Thinktomorrow\Chief\Urls\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Manager;

class UrlAssistant implements Assistant
{
    private $manager;

    private $model;

    private $urlRecords;

    public function manager(Manager $manager)
    {
        $this->manager  = $manager;

        if (! $manager->model() instanceof ProvidesUrl) {
            throw new \Exception('UrlAssistant requires the model interfaced by ' . ProvidesUrl::class . '.');
        }

        $this->model = $manager->model();

        $this->urlRecords = MemoizedUrlRecord::getByModel($this->model);
    }

    public static function key(): string
    {
        return 'url';
    }

    public function route($verb): ?string
    {
        $routes = [
            'check' => route('chief.back.assistants.url.check', [$this->manager->details()->key, $this->manager->model()->id]),
        ];

        return $routes[$verb] ?? null;
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('url-slugs')
                ->validation(
                    [
                        'url-slugs' => ['array', 'min:1', new UniqueUrlSlugRule(($this->model && $this->model->exists) ? $this->model : null),],
                    ],
                    [],
                    [
                        'url-slugs.*' => 'taalspecifieke link',
                    ])
                ->view('chief::back._fields.url-slugs')
                ->viewData(['fields' => UrlSlugFields::fromModel($this->model) ]),
        ]);
    }

    public function saveUrlSlugsField(Field $field, Request $request)
    {
        (new SaveUrlSlugs($this->model))->handle($request->get('url-slugs', []));

        // Push update to homepage setting value
        // TODO: we should just fetch the homepages and push that instead...
        UrlRecord::getByModel($this->model)->reject(function ($record) {
            return ($record->isRedirect() || !$record->isHomepage());
        })->each(function ($record) {
            app(ChangeHomepage::class)->onUrlChanged($record);
        });
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
