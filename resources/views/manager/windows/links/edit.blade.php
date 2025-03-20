@php
    use Thinktomorrow\Chief\Sites\ChiefSites;
@endphp

<div class="border-t border-grey-100 pt-6">
    <div data-form data-form-tags="status,links" class="space-y-6">
        <p class="h6 h1-dark text-lg">Links beheren</p>

        <form id="linksUpdateForm" action="@adminRoute('links-update', $model)" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                @foreach ($linkForm->formValues() as $locale => $formValues)
                    <x-chief::input.group
                        rule="links"
                        class="space-y-2"
                        x-data="{
                        hint: '',
                    }"
                    >
                        <x-chief::form.label for="links.{{ $locale }}">
                            {{ strtoupper($locale) }} link
                        </x-chief::form.label>

                        <x-chief::input.prepend-append>
                            <x-slot name="prepend">
                                @if ($formValues->fixedSegment !== '/')
                                    <span class="flex items-center gap-0.5">
                                        <svg class="h-5 w-5 shrink-0"><use xlink:href="#icon-home" /></svg>

                                        <span class="leading-5">/{{ $formValues->fixedSegment }}/</span>
                                    </span>
                                @else
                                    <svg class="h-5 w-5">
                                        <use xlink:href="#icon-home" />
                                    </svg>
                                @endif
                            </x-slot>

                            <x-chief::input.text
                                id="links.{{ $locale }}"
                                name="links[{{ $locale }}]"
                                value="{{ $formValues->value }}"
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

                        <x-chief::inline-notification type="warning" size="sm" x-html="hint" x-show="hint" />
                    </x-chief::input.group>
                @endforeach
            </div>
        </form>

        @if ($linkForm->hasAnyRedirects())
            <div class="space-y-3">
                <x-chief::form.label>Redirects</x-chief::form.label>

                @foreach ($linkForm->links() as $locale => $links)
                    @if (! $links->redirects->isEmpty())
                        <div class="flex items-start space-x-4">
                            @if (count(ChiefLocales::locales()) > 1)
                                <span class="label label-grey w-8 shrink-0 px-0 text-center text-sm">
                                    {{ $locale }}
                                </span>
                            @endif

                            <div class="w-full px-4 py-3">
                                <div class="-mx-4 -my-3 divide-y divide-grey-200 rounded-lg border border-grey-200">
                                    @foreach ($links->redirects as $urlRecord)
                                        <div x-data class="flex items-center justify-between px-4 py-2">
                                            <div>{{ $urlRecord->slug }}</div>

                                            <x-chief-table::button
                                                x-on:click="() => {
                                                    window.axios.post('{{ route('chief.back.assistants.url.remove-redirect', $urlRecord->id) }}', {
                                                        _method: 'DELETE',
                                                    }).then(function ({ data }) {
                                                        $root.remove();
                                                    });
                                                }"
                                            >
                                                <x-chief::icon.delete />
                                            </x-chief-table::button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <x-chief-table::button type="submit" form="linksUpdateForm" variant="primary">
            Wijzigingen opslaan
        </x-chief-table::button>
    </div>
</div>
