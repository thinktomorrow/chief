<div class="mt-6">
    <x-chief::window>
        <x-slot name="tabs">
            @if (count($sites) > 0)
                <x-chief::window.tabs>
                    @foreach ($sites as $site)
                        <x-chief::window.tabs.item
                            aria-controls="{{ $site->locale }}"
                            aria-selected="{{ $site->locale === $scopedLocale }}"
                            wire:key="model-site-toggle-{{ $site->locale }}"
                            wire:click="set('scopedLocale', '{{ $site->locale }}')"
                            :active="$site->locale == $scopedLocale"
                        >
                            {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($site->locale) }}
                        </x-chief::window.tabs.item>
                    @endforeach
                </x-chief::window.tabs>
            @endif
        </x-slot>
    </x-chief::window>

</div>

{{--<div class="flex justify-end gap-2">--}}
{{--    @if (count($sites) > 0)--}}
{{--        <div>--}}
{{--            @foreach ($sites as $site)--}}
{{--                <x-chief::badge--}}
{{--                    wire:click="set('scopedLocale', '{{ $site->locale }}')"--}}
{{--                    variant="{{ $site->locale == $scopedLocale ? 'blue' : 'outline-transparent' }}"--}}
{{--                    class="cursor-pointer"--}}
{{--                    size="sm"--}}
{{--                >--}}
{{--                    {{ $site->shortName }}--}}
{{--                </x-chief::badge>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}
