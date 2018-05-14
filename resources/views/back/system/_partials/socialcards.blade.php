<section class="row formgroup gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Facebook</h2>
        <p>
            Als je de opgegeven URL deelt op Facebook dan wordt deze weergegeven als een card. De gegevens die je hier invult, zorgen voor de structuur van de Facebook card.
        </p>
    </div>
    <div class="formgroup-input column-8 relative">
        <label for="facebook-url">URL</label>
        <input type="text" name="facebook-url" id="facebook-url" class="input inset-s" placeholder="URL">

        <div class="stack-s">
            <label for="facebook-title">Titel</label>
            <input type="text" name="facebook-title" id="facebook-title" class="input inset-s" placeholder="Titel">
        </div>
        <div class="stack-s relative">
            <label for="facebook-description">Omschrijving</label>
            <textarea class="redactor inset-s" name="facebook-description" id="facebook-description" cols="10" rows="5"></textarea>
        </div>
        <div class="stack-s">
            <label for="facebook-type">Type</label>
            <chief-multiselect name="facebook-type" :options="['website', 'article', 'book', 'books.author', 'books.book', 'books.genre', 'business.business', 'fitness.course', 'game.achievement', 'music.album',  'music.playlist', 'music.radio_station', 'music.song', 'place', 'product', 'product.group', 'product.item', 'profile', 'restaurant.menu', 'restaurant.menu_item', 'restaurant.menu_section', 'restaurant.restaurant', 'video.episode', 'video.movie', 'video.other', 'video.tv_show']">
            </chief-multiselect>
        </div>
        <div class="stack-s">
            <label>Afbeelding</label>
            <label class="custom-file">
                <input type="file" id="facebook-image">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
        <div class="panel --border stack-xl inset">
            <div class="row gutter-s">
                <div class="column-1 center-center --border">
                    <span class="icon icon-facebook icon-2x text-information"></span>
                </div>
                <div class="column-11">
                    <h2 class="text-information clear-margin stack-xs">Crius group</h2>
                    <p class="text-subtle clear-margin center-y">
                        <span class="inline-xs">1 min</span>
                        <span class="inline-xs">&#8729;</span>
                        <span class="icon-small icon icon-globe"></span>
                    </p>
                </div>
            </div>
            <p class="stack">
                Hieronder een voorbeeld van hoe de facebook card er zou uitzien, als de url gedeeld zou worden.
            </p>
            <div class="panel panel-card">
                <div class="card-image --border" style="background-image: url('https://via.placeholder.com/1200x630');"></div>
                <div class="card-content --border inset">
                    <h3>Titel</h3>
                    <p class="clear-margin">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row formgroup gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Twitter</h2>
        <p>
            Als je de opgegeven URL deelt op Twitter dan wordt deze weergegeven als een card. De gegevens die je hier invult, zorgen voor de structuur van de Twitter card.
        </p>
    </div>
    <div class="formgroup-input column-8 relative">
        <label for="facebook-url">URL</label>
        <input type="text" name="twitter-url" id="twitter-url" class="input inset-s" placeholder="URL">

        <div class="stack-s">
            <label for="twitter-title">Titel</label>
            <input type="text" name="twitter-title" id="twitter-title" class="input inset-s" placeholder="Titel">
        </div>
        <div class="stack-s relative">
            <label for="twitter-description">Omschrijving</label>
            <textarea class="redactor inset-s" name="twitter-description" id="twitter-description" cols="10" rows="5"></textarea>
        </div>
        <div class="stack-s">
            <label for="twitter-creator">Gebruikersnaam</label>
            <input type="text" name="twitter-creator" id="twitter-creator" class="input inset-s" placeholder="Gebruikersnaam">
        </div>
        <div class="stack-s">
            <label>Afbeelding</label>
            <label class="custom-file">
                <input type="file" id="twitter-image">
                <span class="custom-file-input" data-title="Kies uw bestand" data-button="Browse"></span>
            </label>
        </div>
        <div class="panel --border stack-xl inset">
            <div class="row gutter-s">
                <div class="column-1 center-center --border">
                    <span class="icon icon-twitter icon-2x text-information"></span>
                </div>
                <div class="column-11">
                    <h2 class="text-information clear-margin stack-xs">Crius group</h2>
                    <p class="text-subtle clear-margin">
                        <span class="inline-xs">@criusgroup</span>
                    </p>
                </div>
            </div>
            <p>
                Hieronder een voorbeeld van hoe de twitter card er zou uitzien, als de url gedeeld zou worden.
            </p>
            <div class="panel panel-card">
                <div class="card-image --border" style="background-image: url('https://via.placeholder.com/876x438');"></div>
                <div class="card-content --border inset">
                    <h3>Titel</h3>
                    <p class="clear-margin">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </div>
        </div>
    </div>
</section>
