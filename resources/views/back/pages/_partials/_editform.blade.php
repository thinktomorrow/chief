<tabs>
    <tab name="Pagina">
        {!! $fields->tagged('pagebuilder')->render() !!}
    </tab>

    @if(\Thinktomorrow\Chief\Modules\Module::anyAvailableForCreation())
        <tab name="modules">
            @include('chief::back.pages._partials.modules')
        </tab>
    @endif

    <tab name="Algemeen">
        @foreach($fields->tagged('general')->merge($fields->untagged()) as $field)
            @formgroup
            @slot('label',$field->getLabel())
            @slot('description',$field->getDescription())
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
            {!! $field->render() !!}
            @endformgroup
        @endforeach
    </tab>
</tabs>
