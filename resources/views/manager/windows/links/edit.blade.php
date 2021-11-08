<div class="space-y-8">
    <h3>Links beheren</h3>

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
                    model-class="{{ get_class($model) }}"
                    model-id="{{ $model->id }}"
                >
                    <div>
                        <div class="mb-1 space-x-1 leading-none">
                            <span class="font-medium display-base display-dark">
                                {{ strtoupper($locale) }} link
                            </span>
                        </div>

                        <div class="space-y-2 mt-2">
                            <div class="flex w-full">
                                <div class="prepend-to-input">
                                    <span v-if="fixedSegment !== '/'" class="flex items-center space-x-1">
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
                                        class="with-prepend"
                                >
                            </div>

                            <div
                                    class="inline-block px-2 py-1 font-medium text-blue-500 rounded-lg bg-blue-50"
                                    v-if="hint"
                                    v-html="hint"
                            ></div>
                        </div>
                    </div>

                </link-input>
            @endforeach
        </div>
    </form>

    @if($linkForm->hasAnyRedirects())
        <div class="space-y-3">
            <h4 class="text-grey-900">Redirects</h4>

            <div class="space-y-3">
                @foreach($linkForm->links() as $locale => $links)
                    @if(!$links->redirects->isEmpty())
                        <div class="flex items-start space-x-4">
                            @if(count(config('chief.locales')) > 1)
                                <span class="flex-shrink-0 w-8 px-0 text-sm text-center label label-grey-light">{{ $locale }}</span>
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
                                                    <x-chief-icon-label type="delete"></x-chief-icon-label>
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
