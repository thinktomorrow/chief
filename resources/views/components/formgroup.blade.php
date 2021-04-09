<div class="space-y-3">
    @if(isset($label) || isset($description))
        <div class="space-y-1">
            @isset($label)
                <h5 class="space-x-1 cursor-default">
                    <span>{{ ucfirst($label) }}</span>

                    @if(isset($isRequired) && $isRequired)
                        <span class="bg-orange-100 text-orange-500 text-xs rounded px-2 py-1">
                            Verplicht veld
                        </span>
                    @endif
                </h5>
            @endisset

            @isset($description)
                <p class="text-grey-500">{!! $description !!}</p>
            @endisset
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
