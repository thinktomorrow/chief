<div class="size-5">
    <button
        type="button"
        x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-paragraph-styles-{{ $locale }}' })"
    >
        <svg class="size-5 text-grey-900" viewBox="0 0 24 24" color="currentColor" fill="none">
            <path
                d="M15 3V21M15 3H10M15 3H21M10 12H7.5C5.01472 12 3 9.98528 3 7.5C3 5.01472 5.01472 3 7.5 3H10M10 12V3M10 12V21"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>
    </button>

    <x-chief::dialog.dropdown id="tiptap-header-paragraph-styles-{{ $locale }}" placement="bottom-center">
        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 1 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M4 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M14 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M17 19H18.5M20 19H18.5M18.5 19V11L17 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M4 12L14 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 1
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 2 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M3.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M13.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M20.5 19H16.5V18.6907C16.5 18.2521 16.5 18.0327 16.5865 17.8385C16.673 17.6443 16.836 17.4976 17.1621 17.2041L19.7671 14.8596C20.2336 14.4397 20.5 13.8416 20.5 13.214V13C20.5 11.8954 19.6046 11 18.5 11C17.3954 11 16.5 11.8954 16.5 13V13.4"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.5 12L13.5 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 2
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 3 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M3.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M13.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M16.5 17C16.5 18.1046 17.3954 19 18.5 19C19.6046 19 20.5 18.1046 20.5 17C20.5 15.8954 19.6046 15 18.5 15C19.6046 15 20.5 14.1046 20.5 13C20.5 11.8954 19.6046 11 18.5 11C17.3954 11 16.5 11.8954 16.5 13"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.5 12L13.5 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 3
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 4 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M3.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M13.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M16.5 11V15H20.5M20.5 15V19M20.5 15V11"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.5 12L13.5 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 4
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 5 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M3.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M13.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M16.5 16.5V17C16.5 18.1046 17.3954 19 18.5 19C19.6046 19 20.5 18.1046 20.5 17V16.5C20.5 15.1193 19.3807 14 18 14H16.5V11H20.5"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.5 12L13.5 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 5
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setHeading({ level: 6 }).run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M3.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M13.5 5V19"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M16.5 17C16.5 18.1046 17.3954 19 18.5 19C19.6046 19 20.5 18.1046 20.5 17C20.5 15.8954 19.6046 15 18.5 15C17.3954 15 16.5 15.8954 16.5 17ZM16.5 17V13C16.5 11.8954 17.3954 11 18.5 11C19.6046 11 20.5 11.8954 20.5 13"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M3.5 12L13.5 12"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
            Heading 6
        </button>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setParagraph().run()
                    close()
                }
            "
            class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
        >
            <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                <path
                    d="M15 21.001H9"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <path
                    d="M12 3.00001V21.0008M12 3.00001C13.3874 3.00001 15.1695 3.03055 16.5884 3.17649C17.1885 3.2382 17.4886 3.26906 17.7541 3.37791C18.3066 3.60429 18.7518 4.10063 18.9194 4.67681C19 4.95382 19 5.26992 19 5.90215M12 3.00001C10.6126 3.00001 8.83047 3.03055 7.41161 3.17649C6.8115 3.2382 6.51144 3.26906 6.24586 3.37791C5.69344 3.60429 5.24816 4.10063 5.08057 4.67681C5 4.95382 5 5.26992 5 5.90215"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                />
            </svg>
            Paragraph
        </button>
    </x-chief::dialog.dropdown>
</div>
