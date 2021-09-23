<div data-fields-component="{{ $componentKey }}" data-{{ $componentKey }}-component>
    @if(public_method_exists($model, 'render'.ucfirst($componentKey).'Component'))
        {!! $model->{'render'.ucfirst($componentKey).'Component'}() !!}
    @else
        <x-chief-card
            title="{{ $title ?? null }}"
            :editRequestUrl="{{ $manager->route('fields-edit', $model, $componentKey) }}"
            sidebarTrigger="data-sidebar-trigger=fields-{{ $componentKey }}"
        >
            <div class="space-y-6">
                @foreach($fields->allFields() as $field)
                    <div class="space-y-2">
                        <h6 class="mb-0">{{ $field->getLabel() }}</h6>

                        <!-- off course, this should be sensible, for all kinds of fieldtypes, checkboxes, images, ... ... -->
                        <!-- as well as localisation ... -->
                        @if($field->getValue())
                            <div>
                                @switch(get_class($field))
                                    @case(\Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField::class)
                                        <p>{{ $field->getValue() }}</p>
                                        @break
                                    @case(\Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField::class)
                                        <img src="{{ $field->getValue() }}" alt="image">
                                        @break
                                    @default
                                        {!! $field->getValue() !!}
                                        @break
                                @endswitch
                            </div>
                        @else
                            <p>...</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-chief-card>
    @endif
</div>
