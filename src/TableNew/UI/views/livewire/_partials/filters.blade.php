<div class="flex items-start gap-2">
    @foreach ($this->getFilters() as $filter)
        {!! $filter->render() !!}
    @endforeach

    <div>
        <button id="table-filters" type="button">
            <x-chief-table-new::button
                color="white"
                iconLeft='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" color="currentColor" fill="none"> <path d="M20.6693 7C20.7527 6.8184 20.7971 6.62572 20.8297 6.37281C21.0319 4.8008 21.133 4.0148 20.672 3.5074C20.2111 3 19.396 3 17.7657 3H6.23433C4.60404 3 3.7889 3 3.32795 3.5074C2.86701 4.0148 2.96811 4.8008 3.17033 6.3728C3.22938 6.8319 3.3276 7.09253 3.62734 7.44867C4.59564 8.59915 6.36901 10.6456 8.85746 12.5061C9.08486 12.6761 9.23409 12.9539 9.25927 13.2614C9.53961 16.6864 9.79643 19.0261 9.93278 20.1778C10.0043 20.782 10.6741 21.2466 11.226 20.8563C12.1532 20.2006 13.8853 19.4657 14.1141 18.2442C14.2223 17.6668 14.3806 16.6588 14.5593 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> <path d="M17.5 8V15M21 11.5L14 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /> </svg>'
            >
                {{-- Voeg filter toe --}}
            </x-chief-table-new::button>
        </button>

        <x-chief::dropdown trigger="#table-filters" placement="bottom-end">
            <button type="button" class="text-left">
                <x-chief::dropdown.item>Filter op tags</x-chief::dropdown.item>
            </button>

            <button type="button" class="text-left">
                <x-chief::dropdown.item>Filter op datum</x-chief::dropdown.item>
            </button>

            <button type="button" class="text-left">
                <x-chief::dropdown.item>Filter op ID</x-chief::dropdown.item>
            </button>
        </x-chief::dropdown>
    </div>
</div>
