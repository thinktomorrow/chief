<div data-fragments-component>
    <div
        data-fragments-container
        data-sortable-fragments
        data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
        class="relative -m-8 border-t border-b divide-y divide-grey-100 border-grey-100"
    >
        @include('chief::manager.cards.fragments.component.fragment-select', [
            'ownerManager' => $manager,
        ])

        @foreach($fragments as $fragment)
            @include('chief::manager.cards.fragments.component._card', [
                'model' => $fragment['model'],
                'manager' => $fragment['manager'],
                'owner' => $owner,
                'ownerManager' => $manager,
                'loop' => $loop,
            ])
        @endforeach
    </div>

{{--    @include('chief::manager.cards.fragments.component.fragment-select', [--}}
{{--        'templateId' => 'js-fragment-template-select-options-main',--}}
{{--    ])--}}
{{--    @include('chief::manager.cards.fragments.component.fragment-select-icon')--}}
</div>
