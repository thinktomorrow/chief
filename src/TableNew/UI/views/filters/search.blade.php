<div class="relative">
    <x-chief::input.text
        wire:model.live.debounce.300ms="filters.{{ $name }}"
        placeholder="{{ $placeholder }}"
        class="py-2 pr-3 leading-5 rounded-lg shadow pl-9 ring-grey-200 ring-1"
    />

    <div class="absolute left-2 top-2">
        <svg class="w-5 h-5 text-grey-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
             fill="currentColor">
            <path fill-rule="evenodd"
                  d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                  clip-rule="evenodd"/>
        </svg>
    </div>
</div>
