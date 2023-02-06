<div>
    <h1>{{ $model->title }}</h1>
</div>
<div>
    <!-- get ancestors -->
    {!! implode(' > ', array_map(fn($model) => $model->title, $model->getAncestors())) !!}

    <!-- get parent page -->
    {!! $model->getParent()?->title !!}

    <!-- get child pages -->
    {!! implode(' > ', array_map(fn($model) => $model->title, $model->getChildren()->all())) !!}

    <!-- get sibling pages -->
    {!! implode(' > ', array_map(fn($model) => $model->title, $model->getSiblings()->all())) !!}

    <!-- get descendants -->
    {!! implode(',', array_map(fn($node) => $node->getModel()->title, $model->getDescendants()->flatten()->all())) !!}
</div>

