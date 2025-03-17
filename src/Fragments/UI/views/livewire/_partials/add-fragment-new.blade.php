@forelse($this->getAllowedFragmentsGrouped() as $category => $fragmentsByCategory)
    <div @class(['space-y-3', 'pt-6 border-t border-grey-100' => !$loop->first, 'pb-6' => !$loop->last])>
        @if($category)
            <div>
                <p class="text-sm tracking-wider uppercase h6 body-dark">{{ ucfirst($category) }}</p>
            </div>
        @endif

        <div class="row-start-stretch gutter-1.5">
            @foreach($fragmentsByCategory as $allowedFragment)
                <div class="w-full sm:w-1/2">
                    <span
                        wire:click="showCreateForm('{{ $allowedFragment::resourceKey() }}')"
                        title="{{ ucfirst($allowedFragment->getLabel()) }}"
                        class="cursor-pointer flex gap-3 p-3 transition-all duration-75 ease-in-out border rounded-lg border-grey-100 bg-grey-50 hover:shadow-card hover:border-primary-500"
                    >
                        <div class="shrink-0 body-dark [&>*]:w-6 [&>*]:h-6">
                            {!! $allowedFragment->getIcon() !!}
                        </div>

                        <div class="space-y-2">
                            <p class="h6 h1-dark">
                                {{ ucfirst($allowedFragment->getLabel()) }}
                            </p>

                            @if($hint = $allowedFragment->getHint())
                                <p class="text-sm body body-dark">
                                    {!! $hint !!}
                                </p>
                            @endif
                        </div>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div class="w-full">
        <p class="body body-dark">Geen fragmenten gevonden.</p>
    </div>
@endforelse
