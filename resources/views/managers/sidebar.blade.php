<div id="js-sidebar-container">
    <!-- sidebars container -->
</div>

@push('custom-scripts-after-vue')
    <template id="js-sidebar-template">
        <div data-sidebar class="fixed inset-0" style="z-index: 50; display: none;">
            <div
                data-sidebar-backdrop
                data-sidebar-close
                class="absolute inset-0 bg-black opacity-25 fade-in"
            ></div>

            <aside
                data-sidebar-aside
                class="absolute top-0 right-0 bottom-0 bg-white shadow-lg p-16 space-y-16 overflow-auto w-1/2 slide-from-right"
            >
                <div
                    data-sidebar-close
                    data-sidebar-close-button
                    class="inline-block cursor-pointer"
                ></div>

                <div data-sidebar-content>
                    <!-- panel content -->
                </div>
            </aside>
        </div>
    </template>

    <template id="js-sidebar-close-button">
        <div class="inline-flex items-center text-primary-500">
            {{-- <svg width="18" height="18"><use xlink:href="#x"></use></svg> --}}
            <svg width="24" height="24" class="fill-current"><use xlink:href="#arrow-left"/></svg>
        </div>
    </template>
@endpush
