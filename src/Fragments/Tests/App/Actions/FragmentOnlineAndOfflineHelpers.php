<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

trait FragmentOnlineAndOfflineHelpers
{
    private function prepareOfflineFragment($owner): Fragment
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($owner, SnippetStub::class, 0, ['online_status' => 'offline']);
        $fragment->getFragmentModel()->setOffline();
        $fragment->getFragmentModel()->save();

        $this->assertFalse($fragment->getFragmentModel()->isOnline());

        return $fragment;
    }

    private function prepareOnlineFragment($owner): Fragment
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($owner, SnippetStub::class, 0, ['online_status' => 'offline']);
        $fragment->getFragmentModel()->setOnline();
        $fragment->getFragmentModel()->save();

        $this->assertTrue($fragment->getFragmentModel()->isOnline());

        return $fragment;
    }
}
