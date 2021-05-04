<div hidden id="{{ $templateId }}">
    <div
        data-fragment-selection-element
        data-sortable-ignore
        class="relative p-8 pop"
    >
        <a data-fragment-selection-element-close class="absolute top-0 right-0 m-2.5 link link-grey cursor-pointer">
            <x-icon-label type="close"></x-icon-label>
        </a>

        <div class="w-full flex justify-center items-center gutter-2">
            <div>
                <a
                        data-sidebar-trigger="selectFragment"
                        href="@adminRoute('fragments-select-new', $owner)"
                        class="flex flex-col justify-center items-center bg-primary-50 rounded-md p-4 space-y-1"
                >
                    <svg class="text-primary-500" width="24" height="24"><use xlink:href="#icon-add"/></svg>
                    <span class="font-medium text-primary-500">Maak een nieuw blok</span>
                </a>
            </div>

            <div>
                <a
                        data-sidebar-trigger="selectFragment"
                        href="@adminRoute('fragments-select-existing', $owner)"
                        class="flex flex-col justify-center items-center bg-primary-50 rounded-md p-4 space-y-1"
                >
                    <svg class="text-primary-500" width="24" height="24"><use xlink:href="#icon-duplicate"/></svg>
                    <span class="font-medium text-primary-500">Kies een bestaand blok</span>
                </a>
            </div>
        </div>
    </div>
</div>
