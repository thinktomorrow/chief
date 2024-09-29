<x-chief-form::editor.button x-on:click="$dispatch('open-dialog', { id: 'tiptap-header-link-dropdown-{{ $locale }}' })">
    <x-chief-form::editor.icon.link />
</x-chief-form::editor.button>

<x-chief::dialog.dropdown id="tiptap-header-link-dropdown-{{ $locale }}" placement="bottom-center">
    <div
        x-data="{
            href: null,
            isNewLink: true,
            isTargetBlank: false,
            classList: null,

            setState() {
                // TODO: Need to reset class too?
                var href = editor().getAttributes('link').href
                var target = editor().getAttributes('link').target

                if (href) {
                    this.isNewLink = false
                } else {
                    this.isNewLink = true
                }

                this.href = href

                if (target === '_blank') {
                    this.isTargetBlank = true
                } else {
                    this.isTargetBlank = false
                }
            },
        }"
        x-on:dropdown-opened.window="
                (event) => {
                    if (
                        event.detail.el ===
                        document.querySelector('#tiptap-header-link-dropdown-{{ $locale }}')
                    ) {
                        setState();
                    }
                }
            "
        class="px-3 py-2"
    >
        <div class="w-64 space-y-3">
            <x-chief::input.group>
                <x-chief::input.label class="text-sm">URL</x-chief::input.label>
                <x-chief::input.text x-model="href" placeholder="https://google.be" />
            </x-chief::input.group>

            <x-chief::input.group inner-class="flex items-start gap-2">
                <x-chief::input.checkbox x-model="isTargetBlank" />
                <x-chief::input.label unset class="body body-dark text-sm leading-6">
                    Open in nieuw tablad
                </x-chief::input.label>
            </x-chief::input.group>

            <x-chief::input.group>
                <x-chief::input.label class="text-sm">Class</x-chief::input.label>
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
            </x-chief::input.group>

            <button
                type="button"
                x-on:click="
                    () => {
                        editor().commands.setLink({
                            href,
                            target: isTargetBlank ? '_blank' : null,
                            rel: isTargetBlank ? 'noopener noreferrer' : null,
                            class: classList == 'link' ? 'link link-primary' : 'btn btn-primary',
                        })
                        close()
                    }
                "
                class="btn btn-primary px-3 py-1 text-sm"
                x-text="isNewLink ? 'Link toevoegen' : 'Link bewerken'"
            ></button>

            <button
                type="button"
                x-on:click="
                    () => {
                        editor().commands.unsetLink()
                        close()
                    }
                "
                class="btn btn-grey"
                x-show="!isNewLink"
            >
                Link verwijderen
            </button>
        </div>
    </div>
</x-chief::dialog.dropdown>
