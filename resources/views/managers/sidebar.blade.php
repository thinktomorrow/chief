<div id="js-sidebar-container">
    <!-- sidebars container -->
</div>

@push('custom-scripts-after-vue')

    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0" style="z-index: 50; display: none;">
            <div data-sidebar-backdrop data-sidebar-close class="absolute inset-0 bg-black opacity-25 fade-in"></div>
            <aside class="absolute top-0 right-0 bottom-0 bg-white shadow-lg p-16 slide-from-right overflow-auto min-w-xl">
                <div data-sidebar-close data-sidebar-close-button class="absolute top-0 left-0 m-4 cursor-pointer"></div>
                <div data-sidebar-content>
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div
            class="
                rounded-full p-2 hover:bg-grey-50 text-grey-500 hover:text-grey-700
                transform hover:scale-110 transition duration-150 ease-in-out
            "
        >
            <svg width="16" height="16"><use xlink:href="#x"></use></svg>
        </div>
    </template>
@endpush
