<modal id="create-module" class="large-modal" title='' :active="{{ ($errors->has('morph_key') || $errors->has('slug')) ? 'true' : 'false' }}">
    <div>
        @foreach(app(\Thinktomorrow\Chief\Modules\Modules::class)->creatableModulesForSelect($owner_type, $owner_id) as $creatableModule)
            <a href="{{ $module['createUrl'] }}">{{ $module['label'] }}</a>
        @endforeach
    </div>
    <div class="stack-s">
        <p class="text-warning">Opgelet. <br>Bewaar eerst deze pagina indien je niet bewaarde aanpassingen hebt.<br>Anders zullen deze verloren gaan.</p>
    </div>
</modal>
