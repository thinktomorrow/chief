<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Site titel</h2>
        <p class="caption">Titel zoals het in search engines (o.a. google) wordt weergegeven.</p>
    </div>
    <div class="formgroup-input column-8">
        <div class="stack-s">
            <input type="text" name="site-name" id="site-name" class="input inset-s" placeholder="Site naam">
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Korte omschrijving</h2>
        <p class="caption">Omschrijving zoals het in search engines (o.a. google) wordt weergegeven.</p>
    </div>
    <div class="formgroup-input column-8">
        <div class="stack-s">
            <textarea class="redactor inset-s" name="description" id="description" cols="10" rows="5"></textarea>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Logo</h2>
        <p class="caption">Upload hier het logo.</p>
    </div>
    <div class="formgroup-input column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="file">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Favicon</h2>
        <p class="caption">Upload hier de favicon.</p>
    </div>
    <div class="formgroup-input column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="file">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Startpagina</h2>
        <p class="caption">Kies hier wat de landingspagina moet zijn van de website.</p>
    </div>
    <div class="formgroup-input column-8">
        <div class="stack">
            <chief-multiselect
            name="paginas"
            :options="[{'label': 'paginas', 'values': ['pagina 1','pagina 2','pagina 3']}]"
            :multiple="true"
            grouplabel="label"
            groupvalues="values"
            placeholder="..."
            >
            </chief-multiselect>
        </div>
    </div>
</section>
