<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlSlugFields;
use Thinktomorrow\Chief\Urls\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Manager;

class UrlAssistant implements Assistant
{
    private $manager;

    private $model;

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function key(): string
    {
        return 'url';
    }

    public function route($verb): ?string
    {
        if($this->manager->hasExistingModel()) {
            $routes = [
                'check' => route('chief.back.assistants.url.check', [
                    $this->manager->details()->key,
                    $this->manager->existingModel()->id,
                ]),
            ];
        }

        return $routes[$verb] ?? null;
    }

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('url-slugs')
                ->validation(
                    [
                        'url-slugs' => [
                            'array',
                            'min:1',
                            new UniqueUrlSlugRule($this->manager->modelInstance(), $this->manager->hasExistingModel() ? $this->manager->existingModel() : null),
                        ],
                    ],
                    [],
                    [
                        'url-slugs.*' => 'taalspecifieke link',
                    ]
                )
                ->view('chief::back._fields.url-slugs')
                ->viewData([
                    'fields' => UrlSlugFields::fromModel($this->manager->hasExistingModel() ? $this->manager->existingModel() : $this->manager->modelInstance()),
                    'checkUrl' => $this->route('check'),
                ])
                ->tag('url'),
        ]);
    }

    public function saveUrlSlugsField(Field $field, Request $request)
    {
        (new SaveUrlSlugs($this->manager->existingModel()))->handle($request->input('url-slugs', []));

        // Push update to homepage setting value
        // TODO: we should just fetch the homepages and push that instead...
        UrlRecord::getByModel($this->manager->existingModel())->reject(function ($record) {
            return ($record->isRedirect() || !$record->isHomepage());
        })->each(function ($record) {
            app(ChangeHomepage::class)->onUrlChanged($record);
        });
    }

    public function can($verb): bool
    {
        return true;
    }

    private function validateModel()
    {
        if (!$this->manager->existingModel() instanceof ProvidesUrl) {
            throw new \Exception('UrlAssistant requires the model interfaced by ' . ProvidesUrl::class . '.');
        }
    }
}
