<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Tests\Actions;

use Thinktomorrow\Chief\Table\Actions\Presets\OnlineStateBulkAction;
use Thinktomorrow\Chief\Table\Actions\Presets\OnlineStateRowAction;
use Thinktomorrow\Chief\Table\Actions\Presets\ViewOnSiteAction;
use Thinktomorrow\Chief\Table\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

final class PublicationActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
    }

    public function test_preview_action_is_only_shown_when_it_has_a_url(): void
    {
        $page = ArticlePage::create();
        $action = ViewOnSiteAction::makeDefault(ArticlePageResource::resourceKey());

        $this->assertFalse(($action->getWhen())(null, $page));

        $url = $this->createUrl($page);
        $page->unsetRelation('urls');

        $this->assertFalse(($action->getWhen())(null, $page));

        $url->changeStatus(LinkStatus::online);
        $url->save();
        $page->unsetRelation('urls');

        $this->assertTrue(($action->getWhen())(null, $page));
    }

    public function test_online_row_action_exposes_the_transition_error(): void
    {
        $page = ArticlePage::create();
        $action = OnlineStateRowAction::makeDefault(ArticlePageResource::resourceKey());

        $result = ($action->getEffect())([], ['item' => (string) $page->modelReference()], $action);

        $this->assertFalse($result);
        $this->assertSame(
            'Publiceren kan pas zodra er minstens één link is toegevoegd.',
            ($action->getNotificationOnFailure())($result, [], [])
        );
    }

    public function test_online_bulk_action_exposes_the_transition_error(): void
    {
        $page = ArticlePage::create();
        $action = OnlineStateBulkAction::makeDefault(ArticlePageResource::resourceKey());

        $result = ($action->getEffect())([], ['items' => [$page->getKey()]], $action);

        $this->assertFalse($result);
        $this->assertSame(
            'Publiceren kan pas zodra er minstens één link is toegevoegd.',
            ($action->getNotificationOnFailure())($result, [], [])
        );
    }

    private function createUrl(ArticlePage $page): UrlRecord
    {
        return UrlRecord::create([
            'site' => 'nl',
            'status' => LinkStatus::offline->value,
            'slug' => 'article',
            'model_type' => $page->getMorphClass(),
            'model_id' => $page->getKey(),
        ]);
    }
}
