@php 
    $level = $level ?? 0; 
    $isFirstLevel = ($level == 0);
    $iconMarginTop = $iconMarginTop ?? '0px';
@endphp

<div 
    data-sortable-menu
    data-sortable-endpoint="#"
    class="divide-y divide-grey-100 {{ $isFirstLevel ? null : 'hierarchy-sub-level' }} {{ $level > 1 ? 'pl-8' : null }}"
>
    <div data-sortable-id="xxx" data-sortable-handle class="relative flex py-3">
        <div 
            class="flex-shrink-0 hidden pr-3 text-grey-700 hierarchy-sub-level:block"
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
        <x-chief-hierarchy 
            :item="$subItem" 
            :level="$level" 
            viewPath="{{ $viewPath }}"
            iconMarginTop="{{ $iconMarginTop ?? '' }}"
        ></x-chief-hierarchy>
    @endforeach
</div>
