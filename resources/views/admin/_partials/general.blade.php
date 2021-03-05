<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2>Site titel</h2>
        <p class="caption">Deze titel wordt gebruikt als SEO-titel.</p>
    </div>
    <div class="column-7">
        <input type="text" name="settings[seo-title]" id="site-name" class="input inset-s" placeholder="Site titel">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2>Korte omschrijving</h2>
        <p class="caption">Deze omschrijving wordt gebruikt als SEO-omschrijving.</p>
    </div>
    <div class="column-7">
        <textarea class="redactor inset-s" name="settings[seo-description]" id="description" cols="10" rows="5"></textarea>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-5">
        <h2>Startpagina</h2>
        <p class="caption">Kies hier de landingspagina van de website.</p>
    </div>
    <div class="column-7">
        <chief-multiselect name="settings[homepage]" :options="['Home', 'Diensten', 'Artikels']">
        </chief-multiselect>
    </div>
</section>
