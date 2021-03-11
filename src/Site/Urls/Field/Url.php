<?php

declare(strict_types = 1);

namespace Thinktomorrow\Chief\Site\Urls\Field;

use Thinktomorrow\Chief\Admin\Settings\Application\ChangeHomepage;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\ValidationRules\UniqueUrlSlugRule;

final class Url
{
    public function save(ProvidesUrl $model, array $input): void
    {
        (new SaveUrlSlugs())->handle($model, data_get($input, 'url-slugs', []));

        // Push update to homepage setting value
        // TODO: we should just fetch the homepages and push that instead...
        UrlRecord::getByModel($model)->reject(function ($record) {
            return ($record->isRedirect() || ! $record->isHomepage());
        })->each(function ($record) {
            app(ChangeHomepage::class)->onUrlChanged($record);
        });
    }

    public function field(ProvidesUrl $model): UrlField
    {
        $checkUrl = app(ModelManagers::class)->find($model)->route('checkUrl', $model);

        return UrlField::make('url-slugs')
                ->validation(
                    [
                        'array',
                        'min:1',
                        new UniqueUrlSlugRule($model, $model),
                    ],
                    [],
                    [
                        'url-slugs.*' => 'taalspecifieke link',
                    ]
                )
                ->view('chief::manager.fieldtypes.url-slugs')
                ->viewData([
                    'fields' => UrlSlugFields::fromModel($model),
                    'checkUrl' => $checkUrl,
                ])
                ->tag('url');
    }
}
