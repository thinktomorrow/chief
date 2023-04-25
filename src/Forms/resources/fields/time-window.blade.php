<p class="body body-dark">
    @if($getValue($locale ?? null))
        {{ \Carbon\Carbon::parse($getValue($locale ?? null))->format('g:i') }}
    @else
        ...
    @endif
</p>
