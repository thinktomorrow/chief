<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h3 class="mb-0">{{ $title }}</h3>

        <div class="flex-shrink-0 flex items-center cursor-pointer">
            <a data-sidebar-{{ $type }}-edit href="{{ $edit_request_url }}" class="link link-black">
                <x-link-label type="edit"></x-link-label>
            </a>
        </div>
    </div>

    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>
