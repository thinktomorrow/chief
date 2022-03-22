@if($getValue($locale ?? null))
    {{ \Carbon\Carbon::parse($getValue($locale ?? null))->format('d/m/Y') }}
@else
    ...
@endif
