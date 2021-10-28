<div>
    <div class="bg-white shadow-sm rounded-2xl">
        <div class="p-6">
            <div class="space-y-1 {{ isset($slot) ? 'pb-6 mb-6 border-b border-grey-100' : null }}">
                <div class="flex items-stretch justify-end space-x-6 leading-tight">
                    @isset($title)
                        <div class="w-full space-x-1 -mt-0.5">
                            <h2 class="inline text-xl font-semibold leading-tight tracking-tight text-grey-900">
                                {!! $title !!}
                            </h2>

                            @isset($labels)
                                {!! $labels !!}
                            @endisset
                        </div>
                    @endisset

                    @isset($button)
                        <div>
                            {!! $button !!}
                        </div>
                    @else
                        <div>
                            <a
                                data-sidebar-trigger="{{ $sidebar ?? null }}"
                                href="{{ $url ?? '#' }}"
                                title="Edit"
                                class="flex-shrink-0 p-2 -m-2 rounded-xl link link-primary bg-primary-100 hover:bg-primary-100"
                            >
                                <x-chief-icon-label type="edit"></x-chief-icon-label>
                            </a>
                        </div>
                    @endisset
                </div>
            </div>

            @isset($slot)
                <div>
                    {!! $slot !!}
                </div>
            @endisset
        </div>
    </div>
</div>
