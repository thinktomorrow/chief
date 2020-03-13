@if ($paginator->lastPage() > 1)
    <div class="pt-10 w-full">
        <ul class="flex justify-center items-center ">
            @if($paginator->currentPage() == 1)
                <li class="cursor-not-allowed opacity-50">
                    <span>Vorige</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->url(1) }}" class="">Vorige</a>
                </li>
            @endif
            
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                @if($paginator->currentPage() == $i)
                    <li class="ml-10 text-l font-bold">
                        <span>{{ $i }}</span>
                    </li>
                @else
                    <li class="ml-10 text-l">
                        <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor
    
    
            @if($paginator->currentPage() == $paginator->lastPage())
                <li class="ml-10 cursor-not-allowed opacity-50">
                    <span>Volgende</span>
                </li>
            @else
                <li class="ml-10">
                    <a href="{{ $paginator->url($paginator->currentPage()+1) }}">Volgende</a>
                </li>
            @endif
        </ul>
    </div>
@endif