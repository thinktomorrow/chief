<div hidden id="{{ $templateId }}">
    <div
        data-fragment-selection-element
        data-sortable-ignore
        class="relative p-8 pop"
    >
        <a data-fragment-selection-element-close class="absolute top-0 right-0 m-2.5 link link-grey cursor-pointer">
            <x-icon-label type="close"></x-icon-label>
        </a>

        <div class="flex flex-col justify-center items-center space-y-4">
            <div class="w-full row-center-stretch gutter-2">
                <div class="w-1/2">
                    <a
                        data-sidebar-trigger="selectFragment"
                        href="@adminRoute('fragments-select-new', $owner)"
                        class="flex flex-col justify-center items-center bg-grey-100 rounded-md p-4 space-y-1"
                    >
                        <svg class="text-grey-700" width="24" height="24"><use xlink:href="#icon-add"/></svg>
                        <span class="font-medium text-grey-700">Maak een nieuw blok</span>
                    </a>
                </div>

                <div class="w-1/2">
                    <a
                        data-sidebar-trigger="selectFragment"
                        href="@adminRoute('fragments-select-existing', $owner)"
                        class="flex flex-col justify-center items-center bg-grey-100 rounded-md p-4 space-y-1"
                    >
                        <svg class="text-grey-700" width="24" height="24"><use xlink:href="#icon-duplicate"/></svg>
                        <span class="font-medium text-grey-700">Kies een bestaand blok</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
