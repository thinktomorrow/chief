@foreach($linkForm->links() as $locale => $links)
    <h3>{{ $locale }}</h3>
    <div>CURRENT: {{ optional($links->current)->slug }}</div>
    <div data-vue-fields>
        @foreach($links->redirects as $urlRecord)
            <url-redirect inline-template removeurl="{{ route('chief.back.assistants.url.remove-redirect',$urlRecord->id) }}">
                <div v-show="!this.removed" class="bg-white panel inset-s stack-s">
                    <span class="text-error cursor-pointer" style="float:right; padding:2px 5px;" @click="remove">
                        <svg class="fill-current" width="18" height="18"><use xlink:href="#trash"/></svg>
                    </span>
                    <div>{{ $urlRecord->slug }}</div>
                </div>
            </url-redirect>
        @endforeach
    </div>
@endforeach


<form action="@adminRoute('links-update', $model)" method="POST">
    @csrf
    @method('PUT')

    <div data-vue-fields>
        @foreach($linkForm->formValues() as $locale => $formValues)

            <link-input inline-template
                        checkurl="{{ route('chief.back.links.check') }}"
                        initial-value="{{ old('links.'.$locale, $formValues->value) }}"
                        fixed-segment="{{ $formValues->fixedSegment }}"
                        model-class="{{ get_class($model) }}"
                        model-id="{{ $model->id }}" >
                <div>
                    <label for="links.{{ $locale }}">{{ $locale }}</label>
                    <span>fixed segment: <span v-text="fixedSegment"></span></span>
                    <input @keyup="onInput" id="links.{{ $locale }}" class="input inset-s" type="text" name="links[{{ $locale }}]" v-model="value" />
                    <span v-if="is_homepage" class="inline-block label label--primary">Homepage link</span>
                    <p class="inline-block right text-error" v-if="hint" v-html="hint"></p>
                </div>
            </link-input>
        @endforeach
    </div>

    <button class="btn btn-primary mt-4" type="submit">Pas aan</button>
</form>
