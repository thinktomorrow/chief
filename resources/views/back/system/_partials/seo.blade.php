<section class="row formgroup gutter">
    <div class="column-4">
        <h2 class="formgroup-label">SEO Titel</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="seo-title" id="seo-title" class="input inset-s" placeholder="Seo titel">
    </div>
</section>
<section class="row formgroup gutter">
    <div class="column-4">
        <h2 class="formgroup-label">SEO Omschrijving</h2>
    </div>
    <div class="formgroup-input column-8 relative">
        <textarea name="seo-description" id="seo-description" class="redactor input inset-s" cols="10" rows="5"></textarea>
    </div>
</section>
<section class="row formgroup gutter">
    <div class="column-4">
        <h2 class="formgroup-label">SEO Sleutelwoorden</h2>
    </div>
    <div class="formgroup-input column-8">
        <chief-multiselect
        name="seo-keywords"
        :options="[{'label': 'first-group', 'values': ['first','second','third']}, {'label': 'second-group', 'values': ['fourth','fifth','sixth']}]"
        :multiple="true"
        grouplabel="label"
        groupvalues="values"
        >
    </chief-multiselect>
</div>
</section>

<section class="row formgroup gutter">
    <div class="column-4">
        <h2 class="formgroup-label">SEO Afbeelding</h2>
    </div>
    <div class="formgroup-input column-8 relative">
        <label class="custom-file">
            <input type="file" id="twitter-image">
            <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
        </label>
        <div class="stack-xl">
            <label for="seo-title"><i>Preview</i></label>
            <div class="panel seo-preview --border inset bc-success">
                <h2 class="text-information">SEO Titel</h2>
                <span class="link text-success">https://crius-group.com/page</span>
                <p class="caption">preview van description tekst hier</p>
            </div>
        </div>
    </div>
</section>
