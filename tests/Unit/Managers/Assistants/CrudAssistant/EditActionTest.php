<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\CrudAssistant;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

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
