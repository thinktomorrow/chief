<div class="flex items-center space-x-3">
    <h1>{!! $fields->first()->getValue() !!}</h1>

    <div class="flex-shrink-0">
        <a
            data-sidebar-trigger="{{ $tag ?: '' }}"
            href="{{ $manager->route('fields-edit', $model, $tag) }}"
            title="Titel aanpassen"
            class="inline-block p-1.5 rounded-xl bg-primary-50 icon-label link link-primary"
        >
            <x-chief-icon-label type="edit" size="18"></x-chief-icon-label>
        </a>
    </div>
</div>
