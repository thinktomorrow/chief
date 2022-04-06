<div data-form data-form-tags="fragments" class="space-y-6">
    <p class="text-lg display-base display-dark">Voeg een nieuw fragment toe</p>

    <div class="row-start-stretch gutter-1.5">
        @forelse($fragments as $category => $fragmentsByCategory)
            @if($category)
                <h2 class="w-full mt-8 text-lg font-bold uppercase">{{ $category }}</h2>
            @endif

            @foreach($fragmentsByCategory as $allowedFragment)
                <div class="w-full sm:w-1/2">
                    <a
                        data-sidebar-trigger
                        href="{{ $allowedFragment['manager']->route('fragment-create', $owner) . (isset($order) ? '?order=' . $order : '') }}"
                        title="{{ ucfirst($allowedFragment['resource']->getLabel()) }}"
                        class="block p-3 space-y-3 transition-all duration-75 ease-in-out border rounded-lg border-grey-100 hover:shadow-card hover:border-primary-500"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg shrink-0 bg-grey-50 text-grey-500 children:w-6 children:h-6">
                                {!! $allowedFragment['resource']->getIcon() !!}
                            </div>

                            <p class="leading-tight display-base display-dark">
                                {{ ucfirst($allowedFragment['resource']->getLabel()) }}
                            </p>
                        </div>

                        @if($hint = $allowedFragment['resource']->getHint())
                            <p class="text-sm body-base body-dark">
                                {!! $hint !!}
                            </p>
                        @endif
                    </a>
                </div>
            @endforeach
        @empty
            <p class="body-base body-dark">Geen fragmenten gevonden.</p>
        @endforelse
    </div>
</div>
