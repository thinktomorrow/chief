<div class="stack-s">
    <label for="trans-{{ $locale }}-seo_title">Seo titel</label>
    <input type="text" name="trans[{{ $locale }}][seo_title]" id="trans-{{ $locale }}-seo_title" class="input inset-s" placeholder="Seo titel" value="{{ old('trans.'.$locale.'.seo_title',$page->translateForForm($locale,'seo_title')) }}">
</div>

<error class="caption text-warning" field="trans.{{ $locale }}.seo_title" :errors="errors.get('trans.{{ $locale }}')"></error>

<div class="stack">
    <label for="trans-{{ $locale }}-seo_description">Seo omschrijving</label>
    <textarea class="inset-s" name="trans[{{ $locale }}][seo_description]" id="trans-{{ $locale }}-seo_description" cols="30" rows="10">{{ old('trans.'.$locale.'.seo_description',$page->translateForForm($locale,'seo_description')) }}</textarea>
</div>

<label for="seo-title"><i>Preview</i></label>
<div class="panel seo-preview --border inset bc-success">
    <h2 class="text-information --remove-margin">Crius Group - {{ old('trans.'.$locale.'.seo_title',$page->translateForForm($locale,'seo_title')) }}</h2>
    <span class="link text-success">https://crius-group.com/{{ old('trans.'.$locale.'.seo_title',$page->translateForForm($locale,'seo_title')) }}</span>
    <p class="caption">{{ old('trans.'.$locale.'.seo_description',$page->translateForForm($locale,'seo_description')) }}</p>
</div>