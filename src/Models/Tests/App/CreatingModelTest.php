<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Models\Tests\App;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Models\App\Actions\CreateModel;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class CreatingModelTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);

        ArticlePageResource::setFieldsDefinition(function () {
            return [
                Form::make('main')->items([
                    Text::make('title_trans')->locales()->required(),
                ]),
            ];
        });
    }

    public function test_it_can_create_model()
    {
        Event::fake();

        $modelId = app(ModelApplication::class)->create(new CreateModel(
            ArticlePage::class,
            ['nl', 'en'],
            ['title_trans' => ['nl' => 'model titel', 'en' => 'model title']],
            []
        ));

        $model = ArticlePage::find($modelId);
        $this->assertEquals('model titel', $model->dynamic('title_trans', 'nl'));
        $this->assertEquals('model title', $model->dynamic('title_trans', 'en'));
        $this->assertEquals(['nl', 'en'], $model->getAllowedSites());

        Event::assertDispatched(ManagedModelCreated::class);
    }
}
