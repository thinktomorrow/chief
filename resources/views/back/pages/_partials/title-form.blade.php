<div class="stack-s">
        <label for="trans-{{ $locale }}-title">Titel</label>
        <input type="text" name="trans[{{ $locale }}][title]" id="trans-{{ $locale }}-title" class="input inset-s" placeholder="Titel" value="{{ old('trans.'.$locale.'.title', $page->translateForForm($locale,'title')) }}">
        <span class="stack inline-block text-default">
            <label>Url:</label>
            <input class="inset-s" type="text" name="trans[{{$locale}}][slug]" value="{{ old('trans.'.$locale.'.slug', $page->translateForForm($locale,'slug')) }}">
        </span>
    </div>

    <error class="caption text-warning" field="trans.{{ $locale }}.title" :errors="errors.get('trans.{{ $locale }}')"></error>
    <error class="caption text-warning" field="trans.{{ $locale }}.slug" :errors="errors.get('trans.{{ $locale }}')"></error>