<div data-form data-form-tags="fragments" class="border-grey-100 space-y-6 border-t pt-6">
    <p class="h6 h1-dark text-lg leading-none">Voeg een nieuw fragment toe</p>

    <div>
        @forelse ($fragments as $category => $fragmentsByCategory)
            <div @class(['space-y-3', 'border-grey-100 border-t pt-6' => ! $loop->first, 'pb-6' => ! $loop->last])>
                @if ($category)
                    <div>
                        <p class="h6 body-dark text-sm tracking-wider uppercase">{{ ucfirst($category) }}</p>
                    </div>
                @endif

                <div class="gutter-1 flex flex-wrap items-stretch justify-start">
                    @foreach ($fragmentsByCategory as $allowedFragment)
                        <div class="w-full sm:w-1/2">
                            <a
                                data-sidebar-trigger
                                href="{{ route('chief::fragments.create', [$context->id, $allowedFragment::resourceKey()]) . (isset($order) ? '?order=' . $order : '') }}"
                                title="{{ ucfirst($allowedFragment->getLabel()) }}"
                                class="border-grey-100 bg-grey-50 hover:border-primary-500 flex gap-3 rounded-lg border p-3 transition-all duration-75 ease-in-out hover:shadow-sm"
                            >
                                <div class="body-dark shrink-0 [&>*]:h-6 [&>*]:w-6">
                                    {!! $allowedFragment->getIcon() !!}
                                </div>

                                <div class="space-y-2">
                                    <p class="h6 h1-dark">
                                        {{ ucfirst($allowedFragment->getLabel()) }}
                                    </p>

                                    @if ($hint = $allowedFragment->getHint())
                                        <p class="body body-dark text-sm">
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
