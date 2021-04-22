<x-chief-card
    class="{{ isset($class) ? $class : '' }}"
    title="{{ $title ?? null }}"
    :editRequestUrl="$manager->route('fields-edit', $model, $componentKey)"
    type="fields-{{ $componentKey }}"
>
    <div class="space-y-6">
        @foreach($fields as $field)
            <div class="space-y-2">
                <span class="text-sm uppercase font-semibold text-grey-500 tracking-widest">{{ $field->getLabel() }}</span>

                <!-- off course, this should be sensible, for all kinds of fieldtypes, checkboxes, images, ... ... -->
                <!-- as well as localisation ... -->

                {{-- Todo: this condition doesn't work for example for ImageField, as it's value is null --}}
                @if($field->getValue())
                    <div class="prose prose-dark">
                        @switch(get_class($field))
                            @case(\Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField::class)
                                <p>{{ $field->getValue() }}</p>
                                @break
                            @case(\Thinktomorrow\Chief\ManagedModels\Fields\Types\MediaField::class)
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
