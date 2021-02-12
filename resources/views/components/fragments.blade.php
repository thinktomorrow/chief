<div data-fragments-component class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="mb-0">Fragments</h2>

        <div data-sidebar-component="fragmentcount">
            AANTAL: {{ count($fragments) }}
        </div>
    </div>

    <div data-sidebar-component="fragments"
         data-sortable-fragments
         data-sortable-endpoint="@adminRoute('fragments-reorder', $owner)"
         class="row gutter-s">
        @foreach($fragments as $fragment)
            @include('chief::managers.fragments._card', [
                'model' => $fragment['model'],
                'owner' => $owner,
                'manager' => $fragment['manager'],
                'loop' => $loop,
            ])
        @endforeach
    </div>

    <div class="mt-8">
        @forelse($allowedFragments as $allowedFragment)
            <a data-sidebar-fragments-edit
               class="btn btn-primary"
               href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}">
                Voeg een {{ $allowedFragment['model']->adminLabel('label') }} toe
            </a>
        @empty
            No available fragments.
        @endforelse
    </div>
</div>
