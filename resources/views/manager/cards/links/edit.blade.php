<div class="space-y-10">
    <h3>Permalinks beheren</h3>

    <div data-vue-fields>
        @foreach(['publish', 'unpublish'] as $action)
            @adminCan($action, $model)
                @include('chief::manager._transitions.panel.'. $action)
            @endAdminCan
        @endforeach
    </div>

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
                    <div class="mb-4">
                        <h6 class="mb-2">{{ strtoupper($locale) }} link</h6>

                        <div class="flex items-center space-x-3">
                            <span v-text="fixedSegment"></span>
                            <input @keyup="onInput" id="links.{{ $locale }}" class="input inset-s w-full" type="text" name="links[{{ $locale }}]" v-model="value" />

                            <span v-if="is_homepage" class="inline-block label label--primary">Homepage link</span>
                        </div>

                        <p class="inline-block right text-error" v-if="hint" v-html="hint"></p>
                    </div>
                </link-input>
            @endforeach
        </div>
    </form>

    @if($linkForm->hasAnyRedirects())
        <div>
            <h4 class="mb-4">Redirects</h4>
            @foreach($linkForm->links() as $locale => $links)

                @if(!$links->redirects->isEmpty())

                    <div class="flex mb-2">
                        <div class="w-1/5 mt-2">{{ strtoupper($locale) }}</div>
                        <div class="w-full space-y-2">

                            @foreach($links->redirects as $urlRecord)
                                <div data-vue-fields class="space-y-1">
                                    <url-redirect
                                            inline-template
                                            removeurl="{{ route('chief.back.assistants.url.remove-redirect',$urlRecord->id) }}"
                                    >
                                        <div v-show="!this.removed"
                                             class="flex justify-between items-center bg-grey-100 px-3 py-2 rounded-lg">
                                            <div>{{ $urlRecord->slug }}</div>

                                            <span class="link link-black cursor-pointer" @click="remove">
                                    <x-icon-label type="delete"></x-icon-label>
                                </span>
                                        </div>
                                    </url-redirect>
                                </div>
                            @endforeach

                        </div>
                    </div>


                @endif


            @endforeach
        </div>

    @endif

    <button class="btn btn-primary" type="submit" form="linksUpdateForm"> Wijzigingen opslaan </button>
</div>
