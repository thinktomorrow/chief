@php
    $rows = $getRows();
@endphp

<div class="space-y-6">
    <div class="gutter-3 flex flex-wrap items-start justify-start">
        @forelse ($rows as $i => $asset)
            <div wire:key="gallery_{{ $i . '_' . $asset->id }}" class="w-1/2 sm:w-1/3 md:w-1/4 xl:w-1/5 2xl:w-1/6">
                @include('chief-assets::_partials.asset-item', ['withActions' => true])
            </div>
        @empty
            {{-- TODO: add more, friendly instructions in case the media gallery is completely empty --}}
            <div class="prose prose-dark prose-spacing w-full">
                @if (count($getFilters()) > 0)
                    <p>We konden geen bestanden terugvinden voor deze zoekopdracht.</p>
                @else
                    <p>De mediagalerij is momenteel nog leeg.</p>
                @endif
            </div>
        @endforelse
    </div>

    @if ($rows->total() > $rows->count())
        <div>
            {{ $rows->onEachSide(0)->links() }}
        </div>
    @endif
</div>
