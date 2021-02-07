<section class="space-y-2">
    <div>
        @if(isset($label))
            <label class="font-medium">
                {{ ucfirst($label) }}

                @if(isset($isRequired) && $isRequired)
                    <span class="group text-warning text-base">
                        <span class="inline group-hover:hidden">*</span>
                        <span class="hidden group-hover:inline font-semibold">Verplicht veld</span>
                    </span>
                @endif
            </label>
        @endif

        @if(isset($description))
            <p class="caption">{!! $description !!}</p>
        @endif
    </div>

    <div class="input-group">
        {{ $slot }}
    </div>
</section>
