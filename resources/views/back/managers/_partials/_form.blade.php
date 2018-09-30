<?php $fieldArrangement = $manager->fieldArrangement(); ?>

@if($fieldArrangement->hasTabs())
        <tabs>
            @foreach($fieldArrangement->tabs() as $tab)
                <tab name="{{ $tab->title() }}" id="{{ $tab->title() }}">
                    @foreach($tab->fields() as $field)
                        {!! $manager->renderField($field) !!}
                    @endforeach
                </tab>
            @endforeach
        </tabs>
    @else
    @foreach($fieldArrangement->fields() as $field)
        {!! $manager->renderField($field) !!}
    @endforeach
@endif



