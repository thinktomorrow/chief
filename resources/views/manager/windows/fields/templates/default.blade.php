@if(count($fields) > 0)
    <x-chief-card
        class="{{ isset($class) ? $class : '' }}"
        title="{{ $title ?? null }}"
        :editRequestUrl="$manager->route('fields-edit', $model, $componentKey)"
        sidebarTrigger="data-sidebar-trigger={{ $componentKey }}"
    >
        <div class="row-start-start gutter-3">
            @foreach($fields->allFields() as $field)
                <div class="{{ $field->getWidthStyle() }} space-y-2">
                    <span class="text-xs font-semibold uppercase text-grey-700">
                        {{ ucfirst($field->getLabel()) }}
                    </span>

                    {!! $field->renderWindow() !!}
                </div>
            @endforeach
        </div>
    </x-chief-card>
@endif
