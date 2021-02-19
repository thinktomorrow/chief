<div data-fragments-component class="space-y-10">
    <div class="flex justify-between items-center">
        <h2 class="mb-0">Fragments</h2>

        <div data-sidebar-component="fragmentcount">
            <span class="font-medium text-grey-500">Aantal: {{ count($fragments) }}</span>
        </div>
    </div>

    <div data-sidebar-component="fragments"
         data-sortable-fragments
         data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
         class="divide-y divide-grey-100 border-t border-b border-grey-100 -mx-12"
    >
        @foreach($fragments as $fragment)
            @include('chief::managers.fragments._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    {{-- @include('chief::managers.fragments._add', [
        'allowedFragments' => $allowedFragments
    ]) --}}
</div>

@push('custom-scripts-after-vue')
    <template id="js-fragment-selection-template">
        <div
            data-sortable-handle
            data-fragment-selection
            class="w-full px-12 py-12 space-y-4 pop"
        >
            <div>
                <p class="font-medium text-grey-700 text-center">
                    Kies een blok om toe te voegen
                </p>
            </div>

            <div class="flex justify-center items-center space-x-2">
                @forelse($allowedFragments as $allowedFragment)
                    <a
                        data-sidebar-fragments-edit
                        data-sortable-ignore
                        class="bg-primary-50 font-medium text-grey-900 py-1 px-2 rounded-lg"
                        href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}"
                    >
                        {{ ucfirst($allowedFragment['model']->adminLabel('label')) }}
                    </a>
                @empty
                    No available fragments.
                @endforelse
            </div>
        </div>
    </template>
@endpush
