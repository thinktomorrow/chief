<div class="pt-6 border-t border-grey-100">
    <div data-form data-form-tags="status,links" class="space-y-6">
        <p class="text-lg h6 h1-dark">Links beheren</p>

        <form id="linksUpdateForm" action="@adminRoute('links-update', $model)" method="POST">
            @csrf
            @method('PUT')

            <div data-vue-fields class="space-y-4">
                @foreach($linkForm->formValues() as $locale => $formValues)
                    <link-input
                        inline-template
                        checkurl="{{ route('chief.back.links.check') }}"
                        initial-value="{{ old('links.'.$locale, $formValues->value) }}"
                        fixed-segment="{{ $formValues->fixedSegment }}"
                        model-class="{{ $model::class }}"
                        model-id="{{ $model->id }}"
                    >
                        <div>
                            <div class="mb-1 space-x-1 leading-none">
                                <span class="font-medium h6 h1-dark">
                                    {{ strtoupper($locale) }} link
                                </span>
                            </div>

                            <div class="mt-2 space-y-2">
                                <div class="flex w-full form-light">
                                    <div class="form-input-prepend">
                                        <span v-if="fixedSegment !== '/'" class="flex items-center space-x-0.5">
                                            {{-- TODO: better icon --}}
                                            <svg width="20" height="20"><use xlink:href="#icon-home"/></svg>

                                            <span class="flex items-center leading-none">
                                                <span>/</span>
                                                <span v-text="fixedSegment"></span>
                                                <span>/</span>
                                            </span>
                                        </span>

                                        <span v-else>
                                            <svg width="20" height="20"><use xlink:href="#icon-home"/></svg>
                                        </span>
                                    </div>

                                    <input
                                        @keyup="onInput"
                                        id="links.{{ $locale }}"
                                        type="text"
                                        name="links[{{ $locale }}]"
                                        v-model="value"
                                        class="form-input-with-prepend form-input-field"
                                    >
                                </div>

                                <div
                                    class="inline-block px-2 py-1 font-medium text-blue-500 rounded-lg bg-blue-50"
                                    v-if="hint"
                                    v-html="hint"
                                ></div>

                                <x-chief::input.error rule="links"/>
                            </div>
                        </div>
                    </link-input>
                @endforeach
            </div>
        </form>

        @if($linkForm->hasAnyRedirects())
            <div class="space-y-3">
                <h4 class="h4 h1-dark">Redirects</h4>

                <div class="space-y-3">
                    @foreach($linkForm->links() as $locale => $links)
                        @if(!$links->redirects->isEmpty())
                            <div class="flex items-start space-x-4">
                                @if(count(config('chief.locales')) > 1)
                                    <span class="w-8 px-0 text-sm text-center shrink-0 label label-grey">{{ $locale }}</span>
                                @endif

                                <div class="w-full px-4 py-3">
                                    <div data-vue-fields class="-mx-4 -my-3 border divide-y rounded-lg border-grey-200 divide-grey-200">
                                        @foreach($links->redirects as $urlRecord)
                                            <url-redirect
                                                inline-template
                                                removeurl="{{ route('chief.back.assistants.url.remove-redirect', $urlRecord->id) }}"
                                            >
                                                <div
                                                    v-show="!this.removed"
                                                    class="flex items-center justify-between px-4 py-3"
                                                >
                                                    <div>{{ $urlRecord->slug }}</div>

                                                    <span class="cursor-pointer link link-error" @click="remove">
                                                        <x-chief::icon-label type="delete"></x-chief::icon-label>
                                                    </span>
                                                </div>
                                            </url-redirect>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <button class="btn btn-primary" type="submit" form="linksUpdateForm">Wijzigingen opslaan</button>
    </div>
</div>
