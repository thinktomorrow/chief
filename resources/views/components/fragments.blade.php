<div data-fragments-component>
    <h2>Fragments</h2>

    <div class="row gutter-s">
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

            <a data-sidebar-show
               class="btn btn-primary"
               href="{{ $allowedFragment['manager']->route('fragment-create', $owner) }}">
                Voeg een {{ $allowedFragment['model']->adminLabel('label') }} toe
            </a>

        @empty
            No available fragments.
        @endforelse
    </div>
</div>
