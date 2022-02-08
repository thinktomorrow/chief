@if(count($results) > 0)
    {{-- <div>{{ $term }}</div> --}}

    <div class="p-3">
        @foreach($results as $result)
            <a
                href="{{ $result['url'] }}"
                title="{{ $result['title'] }}"
                class="flex items-center justify-between p-3 rounded-lg focus:bg-grey-100 group"
            >
                <span class="font-semibold"> {{ $result['title'] }} </span>
                <span class="hidden text-sm text-grey-500 group-focus:inline-block"> Ga naar pagina </span>
            </a>
        @endforeach
    </div>
@endif
