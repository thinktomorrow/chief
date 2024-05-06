<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

trait FragmentOnlineAndOfflineHelpers
{
    private function prepareOfflineFragment($owner): Fragment
    {
        [$context,$fragment] = FragmentTestAssist::createContextAndAttachFragment($owner, SnippetStub::class, 0, ['online_status' => 'offline']);
        $fragment->fragmentModel()->setOffline();
        $fragment->fragmentModel()->save();

        $this->assertFalse($fragment->fragmentModel()->isOnline());

        return $fragment;
    }

    private function prepareOnlineFragment($owner): Fragment
    {
        [$context,$fragment] = FragmentTestAssist::createContextAndAttachFragment($owner, SnippetStub::class, 0, ['online_status' => 'offline']);
        $fragment->fragmentModel()->setOnline();
        $fragment->fragmentModel()->save();

        $this->assertTrue($fragment->fragmentModel()->isOnline());

        return $fragment;
    }
}
