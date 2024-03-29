@php
    $manager = $manager ?? null;
    $level = $level ?? 0;
    $isFirstLevel = ($level == 0);
    $iconMarginTop = $iconMarginTop ?? '0px';
@endphp

<div data-sortable-menu data-sortable-endpoint="#" @class([
    'divide-y divide-grey-100',
    'hierarchy-sub-level' => !$isFirstLevel,
    'pl-8' => $level > 1,
])>
    <div data-sortable-id="xxx" data-sortable-handle class="relative flex py-3">
        <div
            class="hidden pr-3 shrink-0 text-grey-700 hierarchy-sub-level:block"
            style="margin-top: {{ $iconMarginTop }};"
        >
            <svg width="18" height="18"><use xlink:href="#icon-arrow-tl-to-br"/></svg>
        </div>

        <div class="w-full">
            @include($viewPath)
        </div>
    </div>

    @php $level++ @endphp

    @foreach($item->getChildNodes() as $subItem)
        <x-chief::hierarchy
            :manager="$manager"
            :item="$subItem"
            :level="$level"
            viewPath="{{ $viewPath }}"
            iconMarginTop="{{ $iconMarginTop ?? '' }}"
        />
    @endforeach
</div>
