@adminCan('duplicate')
    <button type="button" id="edit-options">
        <x-chief::button>
            <svg class="w-5 h-5">
                <use xlink:href="#icon-ellipsis-vertical"/>
            </svg>
        </x-chief::button>
    </button>

    <x-chief::dropdown trigger="#edit-options">
        @adminCan('duplicate')
            @include('chief::manager._transitions.index.duplicate')
        @endAdminCan
    </x-chief::dropdown>
@endAdminCan
