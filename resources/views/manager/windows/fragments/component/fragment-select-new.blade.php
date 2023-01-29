<div data-form data-form-tags="fragments" class="pt-6 space-y-6 border-t border-grey-100">
    <p class="text-lg leading-none h6 h1-dark">Voeg een nieuw fragment toe</p>

    <div>
        @forelse($fragments as $category => $fragmentsByCategory)
            <div @class(['space-y-3', 'pt-6 border-t border-grey-100' => !$loop->first, 'pb-6' => !$loop->last])>
                @if($category)
                    <div>
                        <p class="text-sm tracking-wider uppercase h6 body-dark">{{ ucfirst($category) }}</p>
                    </div>
                @endif

                <div class="row-start-stretch gutter-1.5">
                    @foreach($fragmentsByCategory as $allowedFragment)
                        <div class="w-full sm:w-1/2">
                            <a
                                data-sidebar-trigger
                                href="{{ $allowedFragment['manager']->route('fragment-create', $owner) . (isset($order) ? '?order=' . $order : '') }}"
                                title="{{ ucfirst($allowedFragment['resource']->getLabel()) }}"
                                class="flex gap-3 p-3 transition-all duration-75 ease-in-out border rounded-lg border-grey-100 bg-grey-50 hover:shadow-card hover:border-primary-500"
                            >
                                <div class="shrink-0 body-dark [&>*]:w-6 [&>*]:h-6">
                                    {!! $allowedFragment['resource']->getIcon() !!}
                                </div>

                                <div class="space-y-2">
                                    <p class="h6 h1-dark">
                                        {{ ucfirst($allowedFragment['resource']->getLabel()) }}
                                    </p>

                                    @if($hint = $allowedFragment['resource']->getHint())
                                        <p class="text-sm body body-dark">
                                            {!! $hint !!}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="w-full">
                <p class="body body-dark">Geen fragmenten gevonden.</p>
            </div>
        @endforelse
    </div>
</div>
