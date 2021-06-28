<div class="space-y-12">
    <h3>Kies een bestaand fragment</h3>

    <form action="{{ $ownerManager->route('fragments-select-existing', $owner) }}">
        <input class="w-full p-2" type="text" name="search">
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <div data-sidebar-component="existingFragments" class="row-start-center gutter-1">

        <?php dump(request()->all()); ?>

        @if(count($sharedFragments) > 0)
            <div class="space-x-2">
                @foreach($sharedFragments as $sharedFragment)
                    <div data-sidebar-close
                         data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) . (isset($order) ? '?order=' . $order : '') }}"
                         class="mb-6 cursor-pointer"
                    >
                        <h4>{{ $sharedFragment['model']->adminConfig()->getPageTitle() }}</h4>
                        <div class="wireframe">
                            {!! $sharedFragment['model']->renderAdminFragment($owner, $loop) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>
                Oei geen fragmenten meer beschikbaar voor deze pagina... <br>Je hebt alle mogelijke fragmentjes al gebruikt!
            </p>
        @endif

    </div>
</div>
