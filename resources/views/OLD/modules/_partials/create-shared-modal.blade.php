<modal id="create-module" class="large-modal">
    <div>
        @foreach(app(\Thinktomorrow\Chief\Modules\Modules::class)->creatableShareableModulesForSelect() as $creatableModule)
            <a href="{{ $module['createUrl'] }}">{{ $module['label'] }}</a>
        @endforeach
    </div>
    <div class="stack-s">
        <p class="text-warning">Opgelet. <br>Bewaar eerst deze pagina indien je niet bewaarde aanpassingen hebt.<br>Anders zullen deze verloren gaan.</p>
    </div>
</modal>
