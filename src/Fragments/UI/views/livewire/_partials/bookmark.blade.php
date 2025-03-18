@if ($fragment->bookmark && count($fragment->urls))
    @foreach ($fragment->urls as $url)
        <div class="flex flex-wrap items-center gap-2">
            <span class="label label-grey">#{{ $fragment->bookmark }}</span>

            <a
                href="{{ $url }}#{{ $fragment->bookmark }}"
                title="Bekijk dit fragment op de website"
                target="_blank"
                rel="noopener"
                class="link link-primary"
            >
                <x-chief::button>
                    <svg>
                        <use xlink:href="#icon-external-link"></use>
                    </svg>
                </x-chief::button>
            </a>

            <x-chief::copy-button
                :content="'#'.$fragment->bookmark"
                successContent="#{{ $fragment->bookmark }} gekopieerd!"
            >
                <x-chief::button>
                    <svg>
                        <use xlink:href="#icon-link"></use>
                    </svg>
                </x-chief::button>
            </x-chief::copy-button>
        </div>
    @endforeach
@endif
