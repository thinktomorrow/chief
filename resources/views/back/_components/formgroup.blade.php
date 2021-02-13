<section>
    @if(isset($label))
        <h4 class="text-base font-semibold group flex items-center space-x-1 mb-1 cursor-default">
            <span>{{ ucfirst($label) }}</span>

            @if(isset($isRequired) && $isRequired)
                <div class="relative flex items-center text-warning">
                    <div class="relative transform group-hover:scale-0 transition-base">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <svg class="absolute left-0 top-0 transform rotate-45" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
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
