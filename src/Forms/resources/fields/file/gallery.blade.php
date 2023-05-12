<?php $rows = $getRows(); ?>

<div class="w-full">
    <div class="space-y-4 card">
        <div>
            <div class="row gutter-3">
                @foreach($rows as $i => $asset)
                    <div wire:key="{{ $i.'_'.$asset->id }}" class="w-1/2 lg:w-1/3 xl:w-1/4 2xl:w-1/5">
                        @include('chief-form::fields.file.gallery-row')
                    </div>
                @endforeach
            </div>
        </div>

        @if ($rows->total() > $rows->count())
            <div>
                {{ $rows->links() }}
            </div>
        @endif
    </div>
</div>