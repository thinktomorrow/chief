<div class="space-y-10">
    <h3>Visibiliteit</h3>

    <form id="linksUpdateForm" action="@adminRoute('links-update', $model)" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div data-vue-fields>
            @foreach($linkForm->formValues() as $locale => $formValues)
                <link-input
                    inline-template
                    checkurl="{{ route('chief.back.links.check') }}"
                    initial-value="{{ old('links.'.$locale, $formValues->value) }}"
                    fixed-segment="{{ $formValues->fixedSegment }}"
                    model-class="{{ get_class($model) }}"
                    model-id="{{ $model->id }}"
                >
                    <div class="space-y-2">
                        <h6 class="mb-0">Actieve URL voor {{ strtoupper($locale) }}</h6>

                        <div class="flex items-center space-x-3">
                            <span v-text="fixedSegment"></span>

                            <input @keyup="onInput" id="links.{{ $locale }}" class="input inset-s" type="text" name="links[{{ $locale }}]" v-model="value" />

                            <span v-if="is_homepage" class="inline-block label label--primary">Homepage link</span>
                        </div>

                        <p class="inline-block right text-error" v-if="hint" v-html="hint"></p>
                    </div>
                </link-input>
            @endforeach
        </div>
    </form>

    @foreach($linkForm->links() as $locale => $links)
        {{-- <div>CURRENT: {{ optional($links->current)->slug }}</div> --}}

        @foreach($links->redirects as $urlRecord)
            <div class="space-y-2">
                <h6 class="mb-0">Redirects voor {{ strtoupper($locale) }}</h6>

                <div data-vue-fields class="space-y-1">
                    <url-redirect
                        inline-template
                        removeurl="{{ route('chief.back.assistants.url.remove-redirect',$urlRecord->id) }}"
                    >
                        <div v-show="!this.removed" class="flex justify-between items-center bg-grey-100 px-3 py-2 rounded-lg">
                            <div>{{ $urlRecord->slug }}</div>

                            <span class="link link-black cursor-pointer" @click="remove">
                                <x-icon-label type="delete"></x-icon-label>
                            </span>
                        </div>
                    </url-redirect>
                </div>
            </div>
        @endforeach
    @endforeach

    <button class="btn btn-primary" type="submit" form="linksUpdateForm"> Wijzigingen opslaan </button>
</div>
