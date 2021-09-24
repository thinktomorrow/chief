@php
    $hasFirstWindowItem = $hasFirstWindowItem ?? false;
    $hasLastWindowItem = $hasLastWindowItem ?? false;
@endphp

@if(count($fieldsets) > 0)
    <div>
        @foreach($fieldsets as $fieldset)
            @php
                $isFirstWindowItem = $loop->first && $hasFirstWindowItem;
                $isLastWindowItem = $loop->last && $hasLastWindowItem;
            @endphp

            @include('chief::manager.fields.form.fieldset', [
                'isFirstWindowItem' => $isFirstWindowItem,
                'isLastWindowItem' => $isLastWindowItem,
                'borderTop' => !$loop->first || !$isFirstWindowItem,
                'borderBottom' => $loop->last && !$isLastWindowItem,
            ])
        @endforeach
    </div>
@endif
