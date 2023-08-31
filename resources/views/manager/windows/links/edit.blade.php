<div class="pt-6 border-t border-grey-100">
    <div data-form data-form-tags="status,links" class="space-y-6">
        <p class="text-lg h6 h1-dark">Links beheren</p>

        <form id="linksUpdateForm" action="@adminRoute('links-update', $model)" method="POST">
            @csrf
            @method('PUT')

            <div data-vue-fields class="space-y-4">
                @foreach($linkForm->formValues() as $locale => $formValues)
                    <x-chief::input.group rule="links" class="space-y-2" x-data="{
                        hint: '',
                    }">
                        <x-chief::input.label for="links.{{ $locale }}">
                            {{ strtoupper($locale) }} link
                        </x-chief::input.label>

                        <x-chief::input.prepend-append>
                            <x-slot name="prepend">
                                @if($formValues->fixedSegment !== '/')
                                    <span class="flex items-center gap-0.5">
                                        <svg class="w-5 h-5 shrink-0"><use xlink:href="#icon-home"/></svg>

                                        <span class="leading-5">
                                            /{{ $formValues->fixedSegment }}/
                                        </span>
                                    </span>
                                @else
                                    <svg class="w-5 h-5"><use xlink:href="#icon-home"/></svg>
                                @endif
                            </x-slot>

                            <x-chief::input.text
                                id="links.{{ $locale }}"
                                name="links[{{ $locale }}]"
                                x-on:input.debounce.300ms="(e) => {
                                    // An empty value is never checked for uniqueness
                                    if (!e.target.value) {
                                        hint = '';
                                        return;
                                    }

                                    const completeSlug = '{{ $formValues->fixedSegment }}' + '/' + e.target.value;

                                    window.axios.post('{{ route('chief.back.links.check') }}', {
                                        modelClass: `{{ str_replace('\\', '\\\\', $model::class) }}`,
                                        modelId: '{{ $model->id }}',
                                        slug: completeSlug.replace(/\/\//, ''),
                                    }).then(({ data }) => {
                                        console.log(data);
                                        hint = data.hint;
                                    });
                                }"
                            />
                        </x-chief::input.prepend-append>

                        <x-chief::inline-notification type="warning" size="sm" x-html="hint" x-show="hint"/>
                    </x-chief::input.group>
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
