<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Bedrijfsnaam</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="company-name" id="company-name" class="input inset-s" placeholder="Bedrijfsnaam">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Adres</h2>
    </div>
    <div class="formgroup-input column-8">
        <div class="row gutter">
            <div class="column-8">
                <label for="company-street">Straat</label>
                <input type="text" name="company-street" id="company-street" class="input inset-s" placeholder="Straat">
            </div>
            <div class="column-4">
                <label for="company-housenumber">Huisnummer</label>
                <input type="text" name="company-housenumber" id="company-housenumber" class="input inset-s" placeholder="Huisnummer">
            </div>
        </div>
        <div class="stack-s">
            <div class="row gutter">
                <div class="column-8">
                    <div class="stack-xs">
                        <label for="company-township">Gemeente</label>
                        <input type="text" name="company-township" id="company-township" class="input inset-s" placeholder="Gemeente">
                    </div>
                </div>
                <div class="column-4">
                    <div class="stack-xs">
                        <label for="company-postalcode">Postcode</label>
                        <input type="text" name="company-postalcode" id="company-postalcode" class="input inset-s" placeholder="Postcode">
                    </div>
                </div>
            </div>
        </div>
        <div class="stack-s">
            <div class="row gutter">
                <div class="column">
                    <label for="company-country">Land</label>
                    <chief-multiselect name="company-country" selected="België" :options="['België','Nederland', 'Frankrijk','Duitsland']">
                    </chief-multiselect>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">Telefoonnummer</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="company-telephone" id="company-telephone" class="input inset-s" placeholder="Telefoonnummer">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">GSM-nummer</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="company-cellphone" id="company-cellphone" class="input inset-s" placeholder="GSM-nummer">
    </div>
</section>
<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">E-mail</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="company-mail" id="company-mail" class="input inset-s" placeholder="E-mail">
    </div>
</section>

<section class="row formgroup gutter-xs">
    <div class="column-4">
        <h2 class="formgroup-label">BTW-nummer</h2>
    </div>
    <div class="formgroup-input column-8">
        <input type="text" name="company-vat" id="company-vat" class="input inset-s" placeholder="BTW-nummer">
    </div>
</section>
