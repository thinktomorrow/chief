<p class="body body-dark">
    @if($getValueOrFallback($locale ?? null))
        {{ \Carbon\Carbon::parse($getValueOrFallback($locale ?? null))->format('d/m/Y') }}
    @else
        ...
    @endif
</p>
