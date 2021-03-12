<div id="js-sidebar-container">
    <!-- sidebars container -->
</div>

@push('custom-scripts-after-vue')
    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0" style="z-index: 50; display: none;">
            <div
                data-sidebar-backdrop
                data-sidebar-close
                class="absolute inset-0 bg-black opacity-25 cursor-pointer sidebar-bg-fade-in"
            ></div>

            <aside
                data-sidebar-aside
                class="absolute top-0 right-0 bottom-0 bg-white shadow-soft p-12 space-y-4 overflow-auto w-1/2 2xl:w-1/3 sidebar-slide-from-right"
            >
                <div
                    data-sidebar-close
                    data-sidebar-close-button
                    class="inline-block cursor-pointer"
                ></div>

                <div data-sidebar-content tabindex="0">
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div class="link link-primary">
            <x-link-label type="back">Ga terug</x-link-label>
        </div>
    </template>
@endpush
