<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Site titel</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="site-name" id="site-name" class="input inset-s" placeholder="Site titel">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Korte omschrijving</h2>
    </div>
    <div class="formgroup-input column-8">
        <textarea class="redactor inset-s" name="description" id="description" cols="10" rows="5"></textarea>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Logo</h2>
    </div>
    <div class="formgroup-input column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="logo">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Favicon</h2>
    </div>
    <div class="formgroup-input column-7">
        <div class="input-group">
            <label class="custom-file">
                <input type="file" id="favicon">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Startpagina</h2>
        <p class="caption">Kies hier de landingspagina van de website.</p>
    </div>
    <div class="formgroup-input column-8">
        <chief-multiselect name="startpage" :options="['Home', 'Diensten', 'Artikels']">
        </chief-multiselect>
    </div>
</section>
