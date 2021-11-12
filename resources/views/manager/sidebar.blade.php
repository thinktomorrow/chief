<div id="js-sidebar-container"></div>

@push('custom-scripts-after-vue')
    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0 z-10" style="display: none;">
            <div
                data-sidebar-backdrop
                data-sidebar-close
                class="absolute inset-0 bg-black opacity-25 cursor-pointer sidebar-bg-fade-in"
            ></div>

            <aside
                data-sidebar-aside
                class="absolute top-0 bottom-0 right-0 w-full px-8 py-12 overflow-auto bg-white shadow-window md:w-192 sidebar-slide-from-right"
            >
                <div data-sidebar-close data-sidebar-close-button class="mb-2"></div>

                <div data-sidebar-content tabindex="0" class="outline-none">
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div class="cursor-pointer link link-primary">
            <x-chief-icon-label type="back">Ga terug</x-chief-icon-label>
        </div>
    </template>
@endpush
