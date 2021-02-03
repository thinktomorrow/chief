<div data-sidebar class="fixed inset-0" style="z-index: 50; display: none;">
    <div data-sidebar-backdrop data-sidebar-back class="absolute inset-0 bg-black opacity-25 fade-in"></div>
    <aside class="absolute top-0 right-0 bottom-0 bg-white shadow-lg p-16 slide-from-right overflow-auto w-1/2">
        <div data-sidebar-back data-sidebar-back-button class="absolute top-0 left-0 m-4 cursor-pointer"></div>
        <div data-sidebar-content>
            {{ $slot }}
        </div>
    </aside>
</div>

@push('custom-scripts-after-vue')
    <template id="js-sidebar-close-button">
        <div class="rounded-full p-1 text-grey-500 bg-grey-50 hover:bg-grey-100 transition duration-150 ease-in-out">
            <svg width="16" height="16"><use xlink:href="#x"></use></svg>
        </div>
    </template>
@endpush
