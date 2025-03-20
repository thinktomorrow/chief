@php
    // Using array_filter to removed null elements in array
    $breadcrumbs = array_filter($breadcrumbs ?? []);
@endphp

@if (count($breadcrumbs) > 0)
    <div class="flex flex-wrap items-start gap-x-2 gap-y-1">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (is_string($breadcrumb))
                <span class="text-sm/6 text-grey-700">
                    {{ teaser(ucfirst($breadcrumb), 64, '...') }}
                </span>
            @elseif (is_array($breadcrumb))
                <a
                    href="{{ visitedUrl($breadcrumb['url']) }}"
                    title="{{ $breadcrumb['label'] }}"
                    class="group inline-flex items-start gap-1.5 text-sm/6"
                >
                    @if (isset($breadcrumb['icon']))
                        <span aria-hidden="true" class="my-0.5 text-grey-500 *:size-5 group-hover:text-primary-500">
                            @if ($breadcrumb['icon'] === strip_tags($breadcrumb['icon']))
                                <x-dynamic-component :component="'chief::icon.' . $breadcrumb['icon']" />
                            @else
                                {!! $breadcrumb['icon'] !!}
                            @endif
                        </span>
                    @endif

                    <span class="text-grey-500 group-hover:text-grey-950">
                        {{ teaser(ucfirst($breadcrumb['label']), 64, '...') }}
                    </span>
                </a>
            @else
                <a
                    href="{{ visitedUrl($breadcrumb->url) }}"
                    title="{{ $breadcrumb->label }}"
                    class="group inline-flex items-start gap-1.5 text-sm/6"
                >
                    @if ($breadcrumb->icon)
                        <span aria-hidden="true" class="my-0.5 text-grey-500 *:size-5 group-hover:text-primary-500">
                            @if ($breadcrumb->icon === strip_tags($breadcrumb->icon))
                                <x-dynamic-component :component="'chief::icon.' . $breadcrumb->icon" />
                            @else
                                {!! $breadcrumb->icon !!}
                            @endif
                        </span>
                    @endif

                    <span class="text-grey-500 group-hover:text-grey-950">
                        {{ teaser(ucfirst($breadcrumb->label), 64, '...') }}
                    </span>
                </a>
            @endif

            @if (! $loop->last)
                <x-chief::icon.chevron-right aria-hidden="true" class="my-1 size-4 text-grey-500" />
            @endif
        @endforeach
    </div>
@endif
