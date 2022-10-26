<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree;

use Thinktomorrow\Vine\Node;
use Thinktomorrow\Vine\Source;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;

final class NestedTreeSource implements Source
{
    private iterable $records;
    public string $sortChildrenBy = 'order';

    public function __construct(iterable $records)
    {
        $this->records = $records;
    }

    public function nodeEntries(): iterable
    {
        return $this->records;
    }

    public function createNode($entry): Node
    {
        if (!$entry instanceof NestedNode) {
            throw new \InvalidArgumentException('Entry is expected to be a ' . NestedNode::class . '.');
        }

        return $entry;
    }
}
