<div class="space-y-3">
    @if(isset($label) || isset($description))
        <div class="space-y-1">
            @isset($label)
                <label class="space-x-1">
                    <span class="font-semibold text-grey-900">{{ ucfirst($label) }}</span>

                    @if(isset($isRequired) && $isRequired)
                        <span class="label label-warning text-xs">
                            Verplicht
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
