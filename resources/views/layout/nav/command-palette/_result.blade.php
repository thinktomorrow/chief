@if(count($results) > 0)
    <div class="p-3 space-y-3">
        @php $duration = 150; @endphp

        @foreach($results as $resultGroup)
            @if(count($resultGroup['results']) !== 0)
                @php $duration += 25; @endphp

                <div>
                    <div
                        class="px-3 py-2 animate-slide-in"
                        style="animation-duration: {{ $duration }}ms;"
                    >
                        <span class="text-sm text-grey-500">{{ ucfirst($resultGroup['label']) }}</span>
                    </div>

                    <div>
                        @foreach ($resultGroup['results'] as $model)
                            @php $duration += 25; @endphp

                            <a
                                href="{{ $model['url'] }}"
                                title="{{ $model['label'] }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg focus:bg-primary-50 group animate-slide-in"
                                style="animation-duration: {{ $duration }}ms;"
                            >
                                <span class="link link-black"> {{ $model['label'] }} </span>
                                <span class="hidden text-sm font-medium text-primary-500 group-focus:inline-block"> Ga naar pagina </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
