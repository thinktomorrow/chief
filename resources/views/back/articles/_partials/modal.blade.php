<modal id="publication-now-article">
    <div v-cloak>
        <div class="column-12">
            <h2 class="formgroup-label">Super. Tijd om je artikel op te slaan. 👍</h2>
            <p class="caption">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
        <div slot="footer" class="stack inline-group-s">
            <button type="submit" class="btn btn-o-primary">Opslaan als draft</button>
            <a @click="showModal('publication-article')" class="btn btn-o-secondary">Plan je artikel in</a>
            <a class="btn btn-link text-secondary" @click="closeModal('publication-now-article')">annuleer</a>
        </div>
    </div>
</modal>
