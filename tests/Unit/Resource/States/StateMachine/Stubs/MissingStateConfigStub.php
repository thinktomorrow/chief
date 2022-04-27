<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine\Stubs;

class MissingStateConfigStub  extends OnlineStateConfigStub
{
    public function getStates(): array
    {
        return [
            'online',
        ];
    }

    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => ['offline'],
                'to' => 'published',
            ],
        ];
    }
}
