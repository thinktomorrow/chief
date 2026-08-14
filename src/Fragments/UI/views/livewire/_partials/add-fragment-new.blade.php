<div class="-my-2 columns-2 gap-6">
    @forelse ($this->getAllowedFragmentsGrouped() as $category => $fragmentsByCategory)
        <div class="inline-block w-full space-y-1 py-2">
            <p class="text-grey-500 text-sm/6 font-normal">
                @if ($category)
                    {{ ucfirst($category) }}
                @else
                    Standaard
                @endif
            </p>

            <div class="-mx-2 flex flex-wrap items-start">
                @foreach ($fragmentsByCategory as $allowedFragment)
                    <button
                        type="button"
                        wire:click="showCreateForm('{{ $allowedFragment::resourceKey() }}')"
                        class="group hover:bg-grey-50 flex w-full items-start gap-2 rounded-xl p-2 text-left"
                    >
                        <div class="text-grey-400 group-hover:text-primary-500 shrink-0 *:size-6">
                            {!! $allowedFragment->getIcon() !!}
                        </div>

                        <div class="grow space-y-0.5">
                            <h3 class="text-grey-800 group-hover:text-grey-950 text-base/6">
                                {{ ucfirst($allowedFragment->getLabel()) }}
                            </h3>

                            @if ($hint = $allowedFragment->getHint())
                                <p class="body text-grey-500 text-sm">{!! $hint !!}</p>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    @empty
        <div class="w-full">
            <p class="body body-dark">Geen fragmenten gevonden.</p>
        </div>
    @endforelse
</div>
