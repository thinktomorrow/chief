<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\PutFragmentOnline;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class PutFragmentOnlineTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_put_fragment_online()
    {
        dump('sisi');
        [$context,$fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'fr', 0, ['online_status' => 'offline']);

        $this->assertFalse($fragment->fragmentModel()->isOnline());

        app(PutFragmentOnline::class)->handle($fragment->getFragmentId());

        $this->assertTrue($fragment->fragmentModel()->isOnline());
    }

    public function test_it_emits_event()
    {

    }

    public function test_putting_online_twice_only_emits_event_once()
    {

    }

    public function test_it_sets_online_for_all_contexts()
    {

    }

}
