<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\ManagedModels\Actions\Duplicate\DuplicatePage;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class DuplicatePageStateTest extends ChiefTestCase
{
    public function test_it_resets_state_to_the_model_default(): void
    {
        ArticlePage::migrateUp();

        $source = ArticlePage::create([
            'current_state' => PageState::published,
        ]);

        $duplicate = app(DuplicatePage::class)->handle($source, 'order');

        $this->assertEquals(PageState::draft, $duplicate->getState(PageState::KEY));
    }

    public function test_it_does_not_assume_draft_is_the_default_state(): void
    {
        ArticlePage::migrateUp();
        ArticlePage::creating(function (ArticlePage $model): void {
            if ($model->getAttribute(PageState::KEY) === null) {
                $model->changeState(PageState::KEY, PageState::archived);
            }
        });

        $source = ArticlePage::create([
            'current_state' => PageState::published,
        ]);

        $duplicate = app(DuplicatePage::class)->handle($source, 'order');

        $this->assertEquals(PageState::archived, $duplicate->getState(PageState::KEY));
    }
}
