{{-- @php
    $positionClass = $positionClass ?? 'bottom-0';
@endphp

<div
    data-fragments-new
    data-sortable-ignore
    data-sortable-id="remove-before-post"
    class="absolute right-0 left-0 {{ $positionClass }} flex justify-center z-1 border-none">
    <div
        data-fragments-new-trigger
        class="absolute link link-black cursor-pointer bg-white rounded-full"
        style="transform: translateY(-12px)"
    >
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
</div> --}}

<div hidden id="js-fragment-add-template">
    <div
        data-fragments-new-trigger
        data-sortable-ignore
        data-sortable-id="remove-before-post"
        class="flex justify-center z-1 border-none">
        <div
            data-fragments-new-trigger
            class="absolute link link-black cursor-pointer bg-white rounded-full"
            style="transform: translateY(-12px)"
        >
            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>
