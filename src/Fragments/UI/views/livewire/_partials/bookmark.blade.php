@if ($fragment->bookmark && count($fragment->urls))
    @foreach ($fragment->urls as $url)
        <div class="flex flex-wrap items-center gap-2">
            <span class="label label-grey">#{{ $fragment->bookmark }}</span>

            <x-chief::button
                href="{{ $url }}#{{ $fragment->bookmark }}"
                title="Bekijk dit fragment op de website"
                target="_blank"
                rel="noopener"
            >
                <x-chief::icon.link-square />
                <span>Kopieer</span>
            </x-chief::button>

            <x-chief::copy-button
                :content="'#'.$fragment->bookmark"
                successContent="#{{ $fragment->bookmark }} gekopieerd!"
            >
                <x-chief::button>
                    <x-chief::icon.link />
                </x-chief::button>
            </x-chief::copy-button>
        </div>
    @endforeach
@endif
