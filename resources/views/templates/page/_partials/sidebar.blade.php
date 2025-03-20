<section role="sidebar" id="js-sidebar-container"></section>

@push('custom-scripts')
    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0 z-20" style="display: none">
            <div
                data-sidebar-backdrop
                data-sidebar-close
                class="sidebar-bg-fade-in absolute inset-0 cursor-pointer bg-black opacity-25"
            ></div>

            <aside
                data-sidebar-aside
                class="sidebar-slide-from-right absolute bottom-0 right-0 top-0 w-full space-y-6 overflow-auto bg-white px-6 py-12 shadow-card md:w-md"
            >
                <div data-sidebar-close data-sidebar-close-button></div>

                <div data-sidebar-content tabindex="0" class="outline-none">
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div class="link link-primary cursor-pointer">
            <x-chief::icon-label type="back">Ga terug</x-chief::icon-label>
        </div>
    </template>
@endpush
