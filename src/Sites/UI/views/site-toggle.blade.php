<div class="flex justify-end gap-2">
    @if (count($sites) > 0)
        <div>
            @foreach ($sites as $site)
                <x-chief::badge
                    wire:click="set('scopedLocale', '{{ $site->locale }}')"
                    variant="{{ $site->locale == $scopedLocale ? 'blue' : 'outline-transparent' }}"
                    class="cursor-pointer"
                    size="sm"
                >
                    {{ $site->shortName }}
                </x-chief::badge>
            @endforeach
        </div>
    @endif
</div>
