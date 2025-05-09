@props([
    'badges' => [],
    'backButton' => null,
])

{{-- Always declare @aware after @props, otherwise the default values will not be applied. --}}
@aware([
    'title' => null,
    'subtitle' => null,
])

<header {{ $attributes->class(['flex shrink-0 items-start justify-between border-b border-grey-100 p-4']) }}>
    <div class="flex items-start gap-2">
        @if ($backButton)
            {{ $backButton }}
        @else
            <x-chief::button
                size="sm"
                variant="grey"
                type="button"
                x-on:click.stop="close()"
                class="mt-[0.1875rem] shrink-0"
            >
                <x-chief::icon.arrow-left />
            </x-chief::button>
        @endif

        @if ($title || $subtitle || count($badges) > 0)
            <div class="mt-[0.375rem] space-y-1.5">
                @if ($title || count($badges) > 0)
                    <div class="flex flex-wrap items-start gap-2">
                        @if ($title)
                            <h2 class="font-display text-xl/6 font-semibold text-grey-950">
                                {{ $title }}
                            </h2>
                        @endif

                        @if (count($badges) > 0)
                            <div class="flex flex-wrap items-center gap-1">
                                @foreach ($badges as $badge)
                                    <x-chief::badge size="sm" :variant="$badge['variant']">
                                        {{ $badge['label'] }}
                                    </x-chief::badge>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @if ($subtitle)
                    <p class="body text-grey-500">
                        {!! $subtitle !!}
                    </p>
                @endif
            </div>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div>
            {{ $slot }}
        </div>
    @endif
</header>
