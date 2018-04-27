<modal id="publication-now-article">
    <div v-cloak>
        <div class="column-12">
            <h2 class="formgroup-label">Super. <br>Tijd om je artikel op te slaan. 👍</h2>
        </div>
    </div>
    <div slot="footer">
        <button type="submit" class="btn btn-o-primary">Opslaan als draft</button>
        <a @click="showModal('publication-article')" class="btn btn-o-secondary">Plan je artikel in</a>
        <a class="btn btn-link text-secondary" @click="closeModal('publication-now-article')">annuleer</a>
    </div>
</modal>
