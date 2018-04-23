<modal type="sidebar" id="publication-article">
    <div v-cloak>
        <div class="column-12">
            <h2 class="formgroup-label">Publiceer je artikel.</h2>
            <p class="caption">Wil je het artikel inplannen?</p>
            <div class="stack column-6">
                <label for="publication-date">Publiceer vanaf</label>
                <input type="datetime-local" name="publication-date" class="squished">
            </div>
            <div class="row">
            <div class="stack column-6">
                <label for="publication-date">Om</label><br>
                <input type="time" name="publication-time" class="squished">
            </div>
            <div class="stack inline-group">
                <a class="btn btn-primary">Plan je artikel in</a>
                <a class="btn btn-link text-secondary" @click="closeModal('publication-article')">annuleer</a>
            </div>
        </div>
    </div>
</modal>
