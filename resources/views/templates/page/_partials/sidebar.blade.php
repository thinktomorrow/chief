<div id="js-sidebar-container"></div>

@push('custom-scripts-after-vue')
    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0 z-20" style="display: none;">
            <div
                data-sidebar-backdrop
                data-sidebar-close
                class="absolute inset-0 bg-black opacity-25 cursor-pointer sidebar-bg-fade-in"
            ></div>

            <aside
                data-sidebar-aside
                class="absolute top-0 bottom-0 right-0 w-full px-6 py-12 space-y-6 overflow-auto bg-white md:w-md sidebar-slide-from-right shadow-card"
            >
                <div data-sidebar-close data-sidebar-close-button></div>

                <div data-sidebar-content tabindex="0" class="outline-none">
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div class="cursor-pointer link link-primary">
            <x-chief::icon-label type="back">Ga terug</x-chief::icon-label>
        </div>
    </template>
@endpush
