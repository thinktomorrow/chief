<p class="body body-dark wrap-anywhere">
    @if (! is_null($value = $getValueOrFallback($locale ?? null)) && $value !== '')
        {{ rescue(fn () => \Carbon\Carbon::parse($value)->format('H:i'), $value, false) }}
    @else
        ...
    @endif
</p>
