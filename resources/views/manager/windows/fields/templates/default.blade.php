@if(count($fields) > 0)
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="{{ $title ?? null }}"
        :editRequestUrl="$manager->route('fields-edit', $model, $componentKey)"
        type="{{ $componentKey }}"
    >
        <div class="space-y-6">
            @foreach($fields->allFields() as $field)
                <div class="space-y-1">
                    <span class="font-medium text-grey-900">{{ ucfirst($field->getLabel()) }}</span>

                    <div class="prose prose-dark">
                        @include('chief::manager.fields.window.types.' . $field->getType(), ['field' => $field])
                    </div>
                </div>
            @endforeach
        </div>
    </x-chief-card>
@endif
