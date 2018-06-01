<?php
declare(strict_types = 1);

namespace Thinktomorrow\Chief\Menu\Tree;

use Vine\Node;
use Vine\Transposable;
use Thinktomorrow\Chief\Pages\Page;

class MenuTreeTransposer implements Transposable
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function key(): string
    {
        return 'id';
    }

    public function parentKey(): string
    {
        return 'parent_id';
    }

    public function entry(Node $node)
    {
        $entry = $node->entry();

        $entry->fullLabel   = implode('/', array_reverse($node->pluckAncestors('label')));
        $entry->fullPath    = implode('/', array_reverse($node->pluckAncestors('url')));
        // $entry->url         = Page::find($type);

        return $entry;
    }
}