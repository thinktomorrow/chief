<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2 class="formgroup-label">Site titel</h2>
        <p class="caption">Deze titel wordt gebruikt als SEO-titel.</p>
    </div>
    <div class="column-7">
        <input type="text" name="site-name" id="site-name" class="input inset-s" placeholder="Site titel">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2 class="formgroup-label">Korte omschrijving</h2>
        <p class="caption">Deze omschrijving wordt gebruikt als SEO-omschrijving.</p>
    </div>
    <div class="column-7">
        <textarea class="redactor inset-s" name="description" id="description" cols="10" rows="5"></textarea>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2 class="formgroup-label">Logo</h2>
    </div>
    <div class="column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="logo">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2 class="formgroup-label">Favicon</h2>
    </div>
    <div class="column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="favicon">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2 class="formgroup-label">Startpagina</h2>
        <p class="caption">Kies hier de landingspagina van de website.</p>
    </div>
    <div class="column-7">
        <chief-multiselect name="startpage" :options="['Home', 'Diensten', 'Artikels']">
        </chief-multiselect>
    </div>
</section>
