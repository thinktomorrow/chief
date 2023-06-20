@php
    $rows = $getRows();
@endphp

<div class="space-y-6">
    <div class="row-start-start gutter-3">
        @foreach($rows as $i => $asset)
            <div wire:key="{{ $i.'_'.$asset->id }}" class="w-1/2 xs:w-1/3 sm:w-1/4 md:w-1/5 lg:w-1/6">
                @include('chief-assets::_partials.asset-item', ['withActions' => true])
            </div>
        @endforeach
    </div>

    @if ($rows->total() > $rows->count())
        <div>
            {{ $rows->links() }}
        </div>
    @endif
</div>
