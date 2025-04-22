{{--
    <x-chief::tabs active-tab="{{ $scopedLocale }}" :show-nav="true" :should-listen-for-external-tab="true" size="base">
    @foreach ($sites as $site)
    <x-chief::tabs.tab
    :tab-id="$site->locale"
    :tab-label="\Thinktomorrow\Chief\Sites\ChiefSites::name($site->locale)"
    />
    @endforeach
    </x-chief::tabs>
--}}

<div class="space-y-6">
    <x-chief::button-group size="base">
        @foreach ($sites as $site)
            <x-chief::button-group.button
                aria-controls="{{ $site->locale }}"
                aria-selected="{{ $site->locale === $scopedLocale ? 'true' : 'false' }}"
                wire:key="model-site-toggle-{{ $site->locale }}"
                wire:click="set('scopedLocale', '{{ $site->locale }}')"
                :active="$site->locale == $scopedLocale"
            >
                {{ \Thinktomorrow\Chief\Sites\ChiefSites::name($site->locale) }}
            </x-chief::button-group.button>
        @endforeach
    </x-chief::button-group>

    {{--
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
    --}}
</div>
