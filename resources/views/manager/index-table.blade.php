@php
    $columns = [
        [ 'title' => '', 'sortable' => false ],
        [ 'title' => 'Productnaam', 'sortable' => true ],
        [ 'title' => 'Online verkoopbaar', 'sortable' => false ],
        [ 'title' => 'Varianten', 'sortable' => false ],
        [ 'title' => 'Status', 'sortable' => true ],
        [ 'title' => '', 'sortable' => false ],
    ];

    $items = [
        [
            'image' => 'https://i.picsum.photos/id/24/150/150.jpg?hmac=jC2D1NEZaS2CzTtVths4y8ojKmkWhNU2CmdOeT9YR5E',
            'title' => 'Groot-Officier in de Leopoldsorde',
            'quotation' => false,
            'status' => 'online',
            'variants' => '3',
        ], [
            'image' => 'https://i.picsum.photos/id/638/150/150.jpg?hmac=D5oquMlkjO6HyU7XmX9mmPwu-3dMkSbwMzenjR_5wDo',
            'title' => 'Plexi staander voor 1 ereteken',
            'quotation' => false,
            'status' => 'offline',
            'variants' => '9',
        ], [
            'image' => 'https://i.picsum.photos/id/210/150/150.jpg?hmac=yMZOdBY03wnTFZsYmGsVrssPvzR64PcESz_oDQkIe3k',
            'title' => 'Ruiter van de 21ste eeuw (L. Verlee)',
            'quotation' => true,
            'status' => 'online',
            'variants' => '4',
        ], [
            'image' => 'https://i.picsum.photos/id/273/150/150.jpg?hmac=Bu5ogF-NhP1Am_Z2qb4GZXtBYPyPKtUWUZ5JEiAnsN8',
            'title' => 'Geboortemedaille',
            'quotation' => false,
            'status' => 'uitverkocht',
            'variants' => '1',
        ], [
            'image' => 'https://i.picsum.photos/id/157/150/150.jpg?hmac=XmF2p8VBaVVKK_hZbNY03Iiozuxkj_xPeIhZzvcD9kA',
            'title' => 'Beveled Diamond Cube (C632)',
            'quotation' => true,
            'status' => 'online',
            'variants' => '6',
        ], [
            'image' => 'https://i.picsum.photos/id/16/150/150.jpg?hmac=SBCdxSur-hB58Ia8cDisHqbcDxYLlSxl70ttjzPrJVI',
            'title' => 'Summit Award (GA02)',
            'quotation' => false,
            'status' => 'offline',
            'variants' => '12',
        ], [
            'image' => 'https://i.picsum.photos/id/971/150/150.jpg?hmac=A-q6Orh1H3mT0RFrsIF_TErzbrmkNq1r_yoZ1cQ69ZQ',
            'title' => 'Samen een stap vooruit',
            'quotation' => false,
            'status' => 'online',
            'variants' => '8',
        ], [
            'image' => 'https://i.picsum.photos/id/521/150/150.jpg?hmac=LgXY8sRtcZMepPeH1D2k7JyVCLQ_L-8IcYCVz5flIP8',
            'title' => 'Huisnummer in koper (rechthoekig - liggend)',
            'quotation' => false,
            'status' => 'online',
            'variants' => '4',
        ], [
            'image' => 'https://i.picsum.photos/id/66/150/150.jpg?hmac=2cqd7x5UZVioa3Dh_Wk1VqZ8Er8YGzSrtiUMyXe350A',
            'title' => 'Witte plastic naambadge met magneet',
            'quotation' => false,
            'status' => 'online',
            'variants' => '1',
        ], [
            'image' => 'https://i.picsum.photos/id/1038/150/150.jpg?hmac=bSAVzm_4uVIKIA2AaQ5TFDxZUcdYoYh7LbIFBfls298',
            'title' => 'Inner Wheel pin op magneet (5 stuks)',
            'quotation' => false,
            'status' => 'online',
            'variants' => '2',
        ]
    ];
@endphp

<x-chief::index :sidebar="false">
    <x-chief::table>
        <x-slot name="search">
            <input type="text" placeholder="Zoek op productnaam ...">
        </x-slot>

        <x-slot name="filters">
            <a href="#" title="..." class="dropdown-link dropdown-link-success">Online</a>
            <a href="#" title="..." class="dropdown-link dropdown-link-error">Offline</a>
            <a href="#" title="..." class="dropdown-link dropdown-link-warning">Gearchiveerd</a>
            <a href="#" title="..." class="dropdown-link dropdown-link-primary">Alle</a>
        </x-slot>

        <x-slot name="actions">
            <a href="#" title="..." class="dropdown-link dropdown-link-primary">Exporteren</a>
            <a href="#" title="..." class="dropdown-link dropdown-link-success">Zet online</a>
            <a href="#" title="..." class="dropdown-link dropdown-link-error">Zet offline</a>
        </x-slot>

        <x-slot name="header">
            <x-chief::table.header>
                <input
                    data-bulk-all-checkbox
                    type="checkbox"
                    name="bulk_all"
                    id="bulk_all"
                    class="with-custom-checkbox"
                >
            </x-chief::table.header>

            @foreach ($columns as $column)
                <x-chief::table.header :sortable="$column['sortable']" class="text-left display-base display-dark">
                    {{ $column['title'] }}
                </x-chief::table.header>
            @endforeach
        </x-slot>

        <x-slot name="body">
            @foreach ([...$items, ...$items] as $item)
                <x-chief::table.row>
                    <x-chief::table.data>
                        <input
                            data-bulk-item-checkbox
                            type="checkbox"
                            name="item_{{ $loop->index }}"
                            id="item_{{ $loop->index }}"
                            class="with-custom-checkbox"
                        >
                    </x-chief::table.data>

                    <x-chief::table.data>
                        <div class="w-10 h-10 overflow-hidden rounded-lg bg-grey-100">
                            <img src="{{ $item['image'] }}" class="object-cover w-full h-full">
                        </div>
                    </x-chief::table.data>

                    <x-chief::table.data class="leading-normal body-base body-dark">
                        {{ $item['title'] }}
                    </x-chief::table.data>

                    <x-chief::table.data class="leading-normal body-base body-dark">
                        {{ $item['quotation'] ? 'Nee' : 'Ja' }}
                    </x-chief::table.data>

                    <x-chief::table.data>
                        {{ $item['variants'] }}
                    </x-chief::table.data>

                    <x-chief::table.data>
                        @if($item['status'] == 'online')
                            <span class="label label-xs label-success">Online</span>
                        @elseif($item['status'] == 'offline')
                            <span class="label label-xs label-error">Offline</span>
                        @elseif($item['status'] == 'uitverkocht')
                            <span class="label label-xs label-grey">Uitverkocht</span>
                        @endif
                    </x-chief::table.data>

                    <x-chief::table.data class="text-right">
                        <a href="#" title="Aanpassen">
                            <x-chief-icon-button icon="icon-edit"></x-chief-icon-button>
                        </a>
                    </x-chief::table.data>
                </x-chief::table.row>
            @endforeach
        </x-slot>
    </x-chief::table>

    @if($models instanceof \Illuminate\Contracts\Pagination\Paginator)
        {!! $models->links('chief::pagination.default') !!}
    @endif
</x-chief::index-table>
