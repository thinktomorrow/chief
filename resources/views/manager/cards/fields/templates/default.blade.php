@if(count($fields) > 0)
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="{{ $title ?? null }}"
        :editRequestUrl="$manager->route('fields-edit', $model, $componentKey)"
        type="fields-{{ $componentKey }}"
    >
        <div class="space-y-4">
            @foreach($fields as $field)
                <div class="space-y-1">
                    <span class="text-sm font-semibold tracking-widest uppercase text-grey-500">{{ $field->getLabel() }}</span>

                    {{-- Todo: this condition doesn't work for example for ImageField, as it's value is null --}}
                    <div class="prose prose-dark">
                        @if(in_array($field->getType(), [
                            {{-- Done --}}
                            'input', 'html', 'number', 'range', 'date', 'phonenumber', 'checkbox', 'radio', 'select',
                            {{-- Still todo --}}
                            'page', 'image', 'file',
                        ]))
                            @include('chief::manager.cards.fields.templates._partials.' . $field->getType(), ['field' => $field])
                        @else
                            <p>...</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-chief-card>
@endif
