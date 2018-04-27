<modal id="publication-now-page">
    <div v-cloak>
        <div class="column-12">
            <h2 class="formgroup-label">Super. <br>Tijd om je pagina op te slaan. 👍</h2>
        </div>
    </div>
    <div slot="footer">
        <button type="submit" class="btn btn-o-primary">Opslaan als draft</button>
        <a @click="showModal('publication-page')" class="btn btn-o-secondary">Plan je pagina in</a>
        <a class="btn btn-link text-secondary" @click="closeModal('publication-now-page')">annuleer</a>
    </div>
</modal>
