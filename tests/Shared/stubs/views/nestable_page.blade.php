<div>
    <h1>{{ $node->getLabel() }}</h1>
</div>
<div>
    <!-- get breadcrumbs -->
    {!! implode(' > ', array_map(fn($node) => $node->getLabel() ,$node->getBreadCrumbs())) !!}

    <!-- get parent page -->
    {!! $node->getParentNode()?->getLabel() !!}

    <!-- get child pages -->
    {!! implode(' > ', array_map(fn($node) => $node->getLabel() ,$node->getChildNodes()->all())) !!}

    <!-- get previous page -->
    {!! $node->getLeftSiblingNode()?->getLabel() !!}

    <!-- get next page -->
    {!! $node->getRightSiblingNode()?->getLabel() !!}
</div>

