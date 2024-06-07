<div class="size-5">
    <button type="button" id="tiptap-header-paragraph-styles">
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

    <x-chief::dropdown trigger="#tiptap-header-paragraph-styles">
        <template x-for="i in 6">
            <button
                type="button"
                x-on:click="
                    () => {
                        editor().chain().focus().setHeading({ level: i }).run()
                        open = false
                    }
                "
                class="inline-flex items-start gap-2 px-3 py-1.5 leading-5 text-grey-900 hover:bg-grey-100"
            >
                <svg class="size-5" viewBox="0 0 24 24" color="currentColor" fill="none">
                    <path
                        d="M6 4V20"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M18 4V20"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                    <path
                        d="M6 12H18"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                <span x-text="'Heading ' + i"></span>
            </button>
        </template>

        <button
            type="button"
            x-on:click="
                () => {
                    editor().chain().focus().setParagraph().run()
                    open = false
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
    </x-chief::dropdown>
</div>
