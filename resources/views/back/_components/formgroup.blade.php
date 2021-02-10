<section>
    @if(isset($label))
        <label class="block font-medium text-grey-900 mb-2">
            {{ ucfirst($label) }}

            @if(isset($isRequired) && $isRequired)
                <span class="group text-warning text-sm">
                    <span class="inline group-hover:hidden">*</span>
                    <span class="hidden group-hover:inline font-semibold">Verplicht veld</span>
                </span>
            @endif
        </label>
    @endif

    @if(isset($description))
        <p class="text-grey-500 mb-4">{!! $description !!}</p>
    @endif

    <div class="input-group mt-3">
        {{ $slot }}
    </div>
</section>
