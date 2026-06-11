<p class="body body-dark wrap-anywhere">
    @if ($getValueOrFallback($locale ?? null))
        {{ \Carbon\Carbon::parse($getValueOrFallback($locale ?? null))->format('H:i') }}
    @else
        ...
    @endif
</p>
