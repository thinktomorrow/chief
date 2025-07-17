<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Models\Tests\App;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\Models\App\Actions\ModelApplication;
use Thinktomorrow\Chief\Models\App\Actions\UpdateModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class UpdatingModelTest extends ChiefTestCase
{
    private ArticlePage $model;

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

        $this->model = ArticlePage::create(
            ['title_trans' => ['nl' => 'oude model titel', 'en' => 'old model title']],
        );
    }

    public function test_it_can_update_form()
    {
        Event::fake();

        app(ModelApplication::class)->updateModel(new UpdateModel(
            $this->model->modelReference(),
            ['nl', 'en'],
            ['title_trans' => ['nl' => 'model titel', 'en' => 'model title']],
            []
        ));

        $this->model->refresh();

        $this->assertEquals('model titel', $this->model->dynamic('title_trans', 'nl'));
        $this->assertEquals('model title', $this->model->dynamic('title_trans', 'en'));

        Event::assertDispatched(ManagedModelUpdated::class);
    }

    public function test_it_can_update_form_for_specific_locale(): void
    {
        app(ModelApplication::class)->updateModel(new UpdateModel(
            $this->model->modelReference(),
            ['en'],
            ['title_trans' => ['en' => 'model title']],
            []
        ));

        $this->model->refresh();

        $this->assertEquals('oude model titel', $this->model->dynamic('title_trans', 'nl'));
        $this->assertEquals('model title', $this->model->dynamic('title_trans', 'en'));
    }
}
