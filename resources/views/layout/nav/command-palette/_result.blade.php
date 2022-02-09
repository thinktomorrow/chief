{{-- {{ dd($results) }} --}}
@if(count($results) > 0)
    {{-- <div>{{ $term }}</div> --}}

    <div class="p-3 space-y-3">
        @foreach($results as $resultGroup)
            @if(count($resultGroup['models']) !== 0)
                <div>
                    <div class="px-3 py-2">
                        <span class="text-sm text-grey-500">{{ ucfirst($resultGroup['label']) }}</span>
                    </div>

                    <div>
                        @foreach ($resultGroup['models'] as $model)
                            <a
                                href="{{ $model['url'] }}"
                                title="{{ $model['label'] }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg focus:bg-primary-50 group"
                            >
                                <span class="link link-grey"> {{ $model['label'] }} </span>
                                <span class="hidden text-sm font-medium text-primary-500 group-focus:inline-block"> Ga naar pagina </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
