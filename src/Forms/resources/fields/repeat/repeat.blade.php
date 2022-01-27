@foreach($getRepeatedComponents($locale ?? null) as $components)
    @foreach($components as $childComponent)
        {{ $childComponent }}
    @endforeach
@endforeach

<button class="btn btn-primary">+ voeg nieuwe blok toe</button>


@push('custom-scripts-after-vue')
    <template data-repeat-template>
        <div>
            <input type="text" name="foobar[]">
            testje
        </div>
    </template>
@endpush

