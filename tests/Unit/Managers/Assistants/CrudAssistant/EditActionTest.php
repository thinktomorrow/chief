<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Managers\Assistants\CrudAssistant;
use Thinktomorrow\Chief\Managers\Assistants\FormsAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\ManagedModelFactory;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class EditActionTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $model = $this->setupAndCreateArticle();

        $this->setupAndCreateQuote($model);
        $this->setupAndCreateSnippet($model);

        $this->asAdmin()->get($this->manager($model)->route('edit', $model))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $model = $this->setupAndCreateArticle();

        $this->setupAndCreateQuote($model);
        $this->setupAndCreateSnippet($model);

        $this->get($this->manager($model)->route('edit', $model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
