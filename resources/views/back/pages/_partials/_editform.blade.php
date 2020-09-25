<tabs>
    <tab name="Pagina">
        {!! $fields->tagged('pagebuilder')->render() !!}
    </tab>

    {{-- don't show the modules tab if there aren't any modules connected to this model. Make sure we do include the creation modal as it is used by the pagebuilder --}}
    @if(\Thinktomorrow\Chief\Modules\Module::anyAvailableForCreation())
        @if($model->modules->isEmpty())
            @push('portals')
                @include('chief::back.modules._partials.create-modal', ['owner_id' => $model->id, 'owner_type' => $model->getMorphClass()])
            @endpush
        @else
            <tab name="Modules">
                @include('chief::back.pages._partials.modules')
            </tab>
        @endif
    @endif

    <tab name="Algemeen">
        @foreach($fields->tagged('general')->merge($fields->untagged()) as $field)
            @formgroup
                @slot('label',$field->getLabel())
                @slot('description',$field->getDescription())
                @slot('isRequired', $field->required())
                {!! $field->render() !!}
            @endformgroup
        @endforeach
    </tab>
    <tab name="Url">
        {!! $fields->tagged('url')->render() !!}

        @include('chief::back.pages._partials.url-redirects', [
            'redirects' => \Thinktomorrow\Chief\Urls\UrlSlugFields::redirectsFromModel($model)
        ])

    </tab>
    <tab name="Seo">
        @foreach($fields->tagged('seo') as $field)
            @formgroup
                @slot('label',$field->getLabel())
                @slot('description',$field->getDescription())
                @slot('isRequired', $field->required())
                {!! $field->render() !!}
            @endformgroup
        @endforeach
    </tab>
</tabs>
