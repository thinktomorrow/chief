<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine\Stubs;

class MalformedStateConfigStub extends OnlineStateConfigStub
{
    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => [false],
            ],
        ];
    }
}
