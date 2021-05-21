<div class="space-y-12">
    <h3>Kies een bestaand fragment</h3>

    <div class="row-start-center gutter-1">

        @if(count($sharedFragments) > 0)
            <div class="space-x-2">
                @foreach($sharedFragments as $sharedFragment)
                    <div
                            data-sidebar-close
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
