<x-chief-card
    class="{{ isset($class) ? $class : '' }}"
    title="{{ $title ?? null }}"
    :editRequestUrl="$manager->route('fields-edit', $model, $componentKey)"
    type="fields-{{ $componentKey }}"
>
    <div class="space-y-4">
        @foreach($fields as $field)
            <div class="space-y-2">
                <h6 class="mb-0">{{ $field->getLabel() }}</h6>

                <!-- off course, this should be sensible, for all kinds of fieldtypes, checkboxes, images, ... ... -->
                <!-- as well as localisation ... -->
                <div>
                    @if($field instanceof \Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField)
                        <p>{{ $field->getValue() }}</p>
                    @elseif($field instanceof \Thinktomorrow\Chief\ManagedModels\Fields\Types\MediaField)
                        {{--  --}}
                    @else
                        {!! $field->getValue() !!}
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-chief-card>
