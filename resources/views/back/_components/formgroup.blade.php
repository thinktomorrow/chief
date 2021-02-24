<div>
    @if(isset($label))
        <h6 class="group flex items-center space-x-2 mb-0 cursor-default">
            <span>{{ ucfirst($label) }}</span>

            @if(isset($isRequired) && $isRequired)
                <div class="relative flex items-center text-warning">
                    <div class="relative transform group-hover:scale-0 transition-150"> * </div>
                    <div class="absolute transform scale-0 group-hover:scale-100 whitespace-no-wrap transition-150 font-medium">Verplicht veld</div>
                </div>
            @endif
        </h6>
    @endif

    @if(isset($description))
        <p class="text-grey-500 mb-4 mt-2">{!! $description !!}</p>
    @endif

    <div class="input-group mt-3">
        {{ $slot }}
    </div>
</div>
