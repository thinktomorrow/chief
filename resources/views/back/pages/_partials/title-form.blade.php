<div class="stack-s" v-cloak>
    <label for="trans-{{ $locale }}-title">Titel</label>
    <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title', $page->translateForForm($locale,'title')) }}">
        <chief-permalink root="{{ url('/') }}" default-path="{{ old('trans.'.$locale.'.slug', $page->translateForForm($locale,'slug')) }}">
            <div class="stack-xs" slot-scope="{ data, fullUrl }">
                <div class="font-s">
                    <span v-text="fullUrl"></span>
                    <span class="text-primary cursor-pointer" v-if="!data.editMode" @click="data.editMode = true">aanpassen</span>
                </div>
                <input v-show="data.editMode" v-model="data.path" class="stack-s inset-s" type="text" name="trans[{{$locale}}][slug]" value="{{ old('trans.'.$locale.'.slug', $page->translateForForm($locale,'slug')) }}">
            </div>
        </chief-permalink>
</div>

<error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>
<error class="caption text-warning" field="trans.{{ $locale }}.slug" :errors="errors.get('trans.{{ $locale }}')"></error>