<div hidden id="{{ $templateId }}">
    <div
        data-fragment-selection-element
        data-sortable-ignore
        class="relative p-8 pop"
    >
        <a data-fragment-selection-element-close class="absolute top-0 right-0 m-8 cursor-pointer link link-grey">
            <x-icon-label type="close"></x-icon-label>
        </a>

        <div class="flex items-center justify-center w-full gutter-2">
            <div>
                <a
                    data-sidebar-trigger="selectFragment"
                    href="@adminRoute('fragments-select-new', $owner)"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-add"/></svg>

                    <span>Maak een nieuw blok</span>
                </a>
            </div>

            <div>
                <a
                    data-sidebar-trigger="selectFragment"
                    href="@adminRoute('fragments-select-existing', $owner)"
                    class="flex flex-col items-center justify-center space-y-1 label label-primary label-xl"
                >
                    <svg width="24" height="24"><use xlink:href="#icon-duplicate"/></svg>

                    <span>Kies een bestaand blok</span>
                </a>
            </div>
        </div>
    </div>
</div>
