@if($templates = chiefSetting('templates', null, []))

    <?php
            $field = \Thinktomorrow\Chief\ManagedModels\Fields\Types\PageField::make('template')
                        ->modelReferencesAsOptions($templates)
                        ->label('Template')
                        ->description('Neem dezelfde paginainhoud en structuur over als de geselecteerde template. <a href="'.route('chief.back.settings.edit').'">Beheer beschikbare templates</a>.');
    ?>

    @formgroup
        @slot('label',$field->getLabel())
        @slot('description',$field->getDescription())
        @slot('isRequired', $field->required())
        {!! $field->render() !!}
    @endformgroup
@endif
