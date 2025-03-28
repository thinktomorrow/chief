@props([
    'title' => null,
    'subtitle' => null,
    'actions' => [],
])

@aware(['title', 'subtitle'])

@if ($title || $subtitle)
    <header {{ $attributes->class(['flex items-start justify-between gap-4 p-4']) }}>
        <div class="mt-[0.1875rem] space-y-2">
            @if ($title)
                <h2 class="font-display text-lg/6 font-semibold text-grey-950">
                    {{ $title }}
                </h2>
            @endif

            @if ($subtitle)
                <p class="body text-grey-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="ml-auto flex shrink-0 items-start justify-end gap-1.5">
            @if ($actions)
                {{ $actions }}
            @endif

            <x-chief::button size="sm" variant="grey" type="button" x-on:click.stop="close()">
                <x-chief::icon.cancel />
            </x-chief::button>
        </div>
    </header>
@endif
