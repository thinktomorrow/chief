<section>
    @if(isset($label))
        <h4 class="text-base font-semibold group flex items-center space-x-2 mb-1 cursor-default">
            <span>{{ ucfirst($label) }}</span>

            @if(isset($isRequired) && $isRequired)
                <div class="relative flex items-center text-warning">
                    <div class="relative transform group-hover:scale-0 transition-base"> * </div>
                    <div class="absolute transform scale-0 group-hover:scale-100 whitespace-no-wrap transition-base font-medium">Verplicht veld</div>
                </div>
            @endif
        </h4>
    @endif

    @if(isset($description))
        <p class="text-grey-500 mb-4">{!! $description !!}</p>
    @endif

    <div class="input-group mt-3">
        {{ $slot }}
    </div>
</section>
