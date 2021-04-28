<div class="space-y-12">
    <h3>Kies een bestaand fragment</h3>

    <div class="row-start-center gutter-1">

        @if(count($sharedFragments) > 0)
            <div>
                <p class="font-medium text-grey-700 text-center">
                    Favorieten
                </p>
            </div>

            <div class="flex justify-center items-center space-x-2">
                @foreach($sharedFragments as $sharedFragment)
                    <a
                            data-sidebar-close
                            data-fragments-add="{{ $sharedFragment['manager']->route('fragment-add', $owner, $sharedFragment['model']) }}"
                            class="bg-grey-100 rounded-md p-4"
                    >
                            {{ ucfirst($sharedFragment['model']->adminConfig()->getPageTitle()) }}
                        </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
