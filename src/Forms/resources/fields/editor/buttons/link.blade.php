{{--
    What needs to be editable:
    - Link url (text, page select)
    - Link title
    - Link target
    - Link style (button, text, icon)
--}}
<div class="size-5">
    <button type="button" id="tiptap-header-link">
        <svg class="size-5 text-grey-900" viewBox="0 0 24 24" color="currentColor" fill="none">
            <path
                d="M9.14339 10.691L9.35031 10.4841C11.329 8.50532 14.5372 8.50532 16.5159 10.4841C18.4947 12.4628 18.4947 15.671 16.5159 17.6497L13.6497 20.5159C11.671 22.4947 8.46279 22.4947 6.48405 20.5159C4.50532 18.5372 4.50532 15.329 6.48405 13.3503L6.9484 12.886"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
            />
            <path
                d="M17.0516 11.114L17.5159 10.6497C19.4947 8.67095 19.4947 5.46279 17.5159 3.48405C15.5372 1.50532 12.329 1.50532 10.3503 3.48405L7.48405 6.35031C5.50532 8.32904 5.50532 11.5372 7.48405 13.5159C9.46279 15.4947 12.671 15.4947 14.6497 13.5159L14.8566 13.309"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
            />
        </svg>
    </button>

    <x-chief::dropdown trigger="#tiptap-header-link">
        <div x-data="{ link: null, title: null }" class="px-3 py-2">
            <div class="w-64 space-y-3">
                <span class="text-sm text-grey-500">Link bewerken</span>

                <x-chief::input.group>
                    <x-chief::input.label>URL</x-chief::input.label>
                    <x-chief::input.text x-model="link" placeholder="https://google.be" />
                </x-chief::input.group>

                <button
                    type="button"
                    x-on:click="
                        () => {
                            console.log(editor().getAttributes('link'))
                            editor().commands.setLink({ href: link })
                            open = false
                        }
                    "
                    class="btn btn-primary"
                >
                    Link toevoegen
                </button>

                <button
                    type="button"
                    x-on:click="
                        () => {
                            editor().commands.unsetLink()
                            open = false
                        }
                    "
                    class="btn btn-grey"
                >
                    Link verwijderen
                </button>
            </div>
        </div>
    </x-chief::dropdown>
</div>
