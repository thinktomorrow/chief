<x-chief::page.template title="Vaste teksten" container="md">
    <x-chief::window>
        <div class="divide-grey-100 divide-y">
            @foreach ($pages as $page)
                <div
                    @class([
                        'flex items-center justify-between gap-4',
                        'pt-3' => ! $loop->first,
                        'pb-3' => ! $loop->last,
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
