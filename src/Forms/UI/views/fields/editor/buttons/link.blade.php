<x-chief-form::editor.button x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-link-dropdown-{{ $locale }}' })">
    <x-chief-form::editor.icon.link />
</x-chief-form::editor.button>

<x-chief::dialog.dropdown id="tiptap-header-link-dropdown-{{ $locale }}" placement="bottom-center">
    <div
        x-data="{
            classList: null,
            currentSelection: null,
            href: null,
            isTargetBlank: false,
            isNewLink: true,
            page: null,
            text: null,
            setState() {
                this.text = selectedText()
                this.currentSelection = editor().state.selection
                this.href = editor().getAttributes('link').href
                this.isTargetBlank = editor().getAttributes('link').target === '_blank'
                this.isNewLink = ! this.href
            },
            setLink() {
                editor().commands.setLink({
                    href: this.href,
                    target: this.isTargetBlank ? '_blank' : null,
                    rel: this.isTargetBlank ? 'noopener noreferrer' : null,
                    class:
                        this.classList == 'btn'
                            ? 'btn btn-primary'
                            : 'link link-primary',
                })
                editor()
                    .chain()
                    .focus()
                    .insertContentAt(
                        {
                            from: this.currentSelection.from,
                            to: this.currentSelection.to,
                        },
                        this.text,
                    )
                    .run()
                close()
            },
            unsetLink() {
                editor().commands.unsetLink()
                close()
            },
        }"
        x-on:dialog-opened.window="
            (event) => {
                if (event.detail.id === 'tiptap-header-link-dropdown-{{ $locale  }}') {
                    setState();
                }
            }
        "
        class="min-w-64 space-y-3.5 p-3.5"
    >
        <x-chief::form.fieldset>
            <x-chief::form.label class="text-sm">Tekst</x-chief::form.label>
            <x-chief::form.input.text x-model="text" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset>
            <div class="flex justify-between gap-2">
                <x-chief::form.label class="text-sm">URL</x-chief::form.label>
                <button
                    type="button"
                    x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-link-modal-{{ $locale }}' })"
                    class="text-sm text-grey-500 hover:underline"
                >
                    Kies een bestaande pagina
                </button>

                <x-chief::dialog.drawer id="tiptap-header-link-modal-{{ $locale }}" title="Kies een pagina">
                    <x-chief::form.fieldset>
                        {{-- <x-chief::form.label class="text-sm">Kies een pagina</x-chief::form.label> --}}
                        <x-chief::multiselect
                            x-model="page"
                            :options="Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::convertOptionsToChoices(
                                Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::toPairs([
                                    '/' => 'Home',
                                    '/products' => 'Producten',
                                    '/product' => 'Product',
                                    '/blog' => 'Blog',
                                    '/blogpost' => 'Blogpost',
                                    '/contact' => 'Contact',
                                ]),
                            )"
                            :selection="['homepage']"
                        />
                    </x-chief::form.fieldset>

                    <x-slot name="footer" class="flex justify-between gap-3">
                        <button type="button" class="shrink-0" x-on:click="close()">
                            <x-chief-table::button>Annuleer</x-chief-table::button>
                        </button>

                        <button
                            type="button"
                            x-on:click="
                                () => {
                                    //TODO: reset page multiselect
                                    href = page
                                    close()
                                }
                            "
                        >
                            <x-chief-table::button color="primary">Kies pagina</x-chief-table::button>
                        </button>
                    </x-slot>
                </x-chief::dialog.drawer>
            </div>
            <x-chief::form.input.text x-model="href" placeholder="https://google.be" />
        </x-chief::form.fieldset>

        <x-chief::form.fieldset inner-class="flex items-start gap-2">
            <x-chief::input.checkbox id="is-target-blank" x-model="isTargetBlank" />
            <x-chief::form.label for="is-target-blank" unset class="body body-dark text-sm leading-5">
                Open in nieuw tablad
            </x-chief::form.label>
        </x-chief::form.fieldset>

        <x-chief::form.fieldset>
            <x-chief::form.label class="text-sm">Layout</x-chief::form.label>
            <x-chief::multiselect
                x-model="classList"
                :options="Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::convertOptionsToChoices(
                    Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions::toPairs([
                        'link' => 'Link',
                        'btn' => 'Knop',
                    ]),
                )"
                :selection="['link']"
            />
        </x-chief::form.fieldset>

        <div class="flex items-start justify-between gap-2">
            <button type="button" x-on:click="setLink()">
                <x-chief-table::button size="sm" color="primary">
                    <span x-text="isNewLink ? 'Link toevoegen' : 'Link bewerken'"></span>
                </x-chief-table::button>
            </button>

            <button type="button" x-on:click="unsetLink()" x-show="!isNewLink">
                <x-chief-table::button size="sm" color="white">Link verwijderen</x-chief-table::button>
            </button>
        </div>
    </div>
</x-chief::dialog.dropdown>
