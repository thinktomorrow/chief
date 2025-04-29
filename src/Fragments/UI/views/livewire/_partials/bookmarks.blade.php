@php
    // $urls = $fragment->urls;
    // TODO(ben): get fragment urls
    $urls = [
        ['url' => '/', 'locale' => 'nl'],
        ['url' => '/fr', 'locale' => 'fr'],
        ['url' => '/fr-fr', 'locale' => 'fr-fr'],
    ];
@endphp

@if (count($urls))
    <div data-slot="form-group" class="space-y-2">
        @if (count($urls) == 1)
            @include('chief-fragments::livewire._partials.bookmark', ['url' => $urls[0]['url'], 'bookmark' => $fragment->bookmark])
        @elseif (count($urls) > 1)
            <x-chief::tabs :show-nav="false" :should-listen-for-external-tab="true">
                @foreach ($urls as $url)
                    <x-chief::tabs.tab tab-id="{{ $url['locale'] }}">
                        @include('chief-fragments::livewire._partials.bookmark', ['url' => $url['url'], 'bookmark' => $fragment->bookmark])
                    </x-chief::tabs.tab>
                @endforeach
            </x-chief::tabs>
        @endif
    </div>
@endif
