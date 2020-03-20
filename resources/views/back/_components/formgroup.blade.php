<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if(isset($label))
            <h2>
                {{ $label }}
                @if(!isset($isRequired) || !$isRequired)
                    <span class="font-xs text-grey-300">(Optioneel)</span>
                @else
                    <span class="font-xs text-warning">(Verplicht)</span>
                @endif
            </h2>
        @endif

        @if(isset($description))
            <p class="caption">{!! $description !!}</p>
        @endif
    </div>

    <div class="input-group column-8">
        {{ $slot }}
    </div>
</section>
