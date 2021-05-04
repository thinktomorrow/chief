<div data-fragments-component>
    <div
        data-fragments-container
        data-sortable-fragments
        data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
        class="relative divide-y divide-grey-100 border-t border-b border-grey-100 -m-8"
    >
        @foreach($fragments as $fragment)
            @include('chief::manager.cards.fragments.component._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    @include('chief::manager.cards.fragments.component.fragment-template-select-options', [
        'templateId' => 'js-fragment-template-select-options-main',
    ])
    @include('chief::manager.cards.fragments.component.fragment-template-open-select-options')
</div>
