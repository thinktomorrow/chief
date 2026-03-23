<x-chief::page.template title="Vaste teksten" container="md">
    <form data-slot="window" method="GET" action="{{ route('squanto.index') }}" class="flex items-center gap-1">
        <x-chief::form.input.search name="search" placeholder="Zoek in vaste teksten ..." autofocus />

        <x-chief::button type="submit" variant="grey">
            <span>Zoek</span>
        </x-chief::button>
    </form>

    <x-chief::window>
        <div class="divide-grey-100 divide-y">
            @foreach ($pages as $page)
                <div
                    @class([
                        'flex items-center justify-between gap-4',
                        'pt-2.5' => ! $loop->first,
                        'pb-2.5' => ! $loop->last,
                    ])
                >
                    <div class="mt-0.75 flex items-start gap-1.5">
                        <a
                            href="{{ route('squanto.edit', $page->slug()) }}"
                            title="{{ ucfirst($page->label()) }}"
                            class="text-grey-800 leading-6 hover:underline hover:underline-offset-2"
                        >
                            {{ ucfirst($page->label()) }}
                        </a>

                        @if ($percentage = $page->completionPercentage())
                            <x-chief::badge :variant="$percentage == 100 ? 'green' : 'orange'" size="sm">
                                {{ $page->completionPercentage() }}%
                            </x-chief::badge>
                        @endif
                    </div>

                    <x-chief::button
                        href="{{ route('squanto.edit',$page->slug()) }}"
                        title="Bewerk"
                        variant="grey"
                        size="sm"
                    >
                        <x-chief::icon.quill-write />
                    </x-chief::button>
                </div>
            @endforeach
        </div>
    </x-chief::window>
</x-chief::page.template>
