@props([
    'title' => null,
    'subtitle' => null,
])

@aware(['title', 'subtitle'])

@if ($title || $subtitle)
    <header {{ $attributes->class(['flex items-start justify-between gap-4 p-4']) }}>
        <div class="mt-[0.1875rem] space-y-2">
            @if ($title)
                <h2 class="text-lg/6 font-medium text-grey-950">
                    {{ $title }}
                </h2>
            @endif

            @if ($subtitle)
                <p class="body text-grey-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <x-chief-table::button
            size="sm"
            variant="grey"
            type="button"
            x-on:click.stop="close()"
            class="ml-auto shrink-0"
        >
            <x-chief::icon.cancel />
        </x-chief-table::button>
    </header>
@endif
