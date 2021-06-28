<div data-fragments-component>
    <div class="relative divide-y divide-grey-100 border-t border-b border-grey-100 -m-8">

        @include('chief::manager.cards.fragments.component.fragment-select', [
            'ownerManager' => $manager,
            'inOpenState' => count($fragments) < 1
        ])

        <div data-fragments-container
             data-sortable-fragments
             data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
             class="divide-y divide-grey-100">
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


    </div>

{{--    @include('chief::manager.cards.fragments.component.fragment-select', [--}}
{{--        'templateId' => 'js-fragment-template-select-options-main',--}}
{{--    ])--}}
{{--    @include('chief::manager.cards.fragments.component.fragment-select-icon')--}}
</div>
