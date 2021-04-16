<div class="space-y-4">
    @if(isset($label) || isset($description))
        <div class="space-y-2">
            @isset($label)
                <label class="text-lg space-x-1">
                    <span>{{ ucfirst($label) }}</span>

                    @if(isset($isRequired) && $isRequired)
                        <span class="label label-warning text-xs">
                            Verplicht veld
                        </span>
                    @endif
                </label>
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
