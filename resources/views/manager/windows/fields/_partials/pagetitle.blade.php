<div class="flex items-center space-x-3">
    <h1>{!! $fields->first()->getValue() !!}</h1>

    <a
        data-sidebar-trigger="{{ $tag ?: '' }}"
        href="{{ $manager->route('fields-edit', $model, $tag) }}"
        title="Titel aanpassen"
        class="flex-shrink-0"
    >
        <x-chief-icon-button icon="icon-edit" />
    </a>
</div>