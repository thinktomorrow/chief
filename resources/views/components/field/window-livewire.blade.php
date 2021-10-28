<div data-fields-component="{{ $tag }}" data-{{ $tag }}-component>
    @if(isset($template))
        @include($template)
    @elseif($tag === 'pagetitle' && count($fields) > 0)
        <div class="flex items-start space-x-4">
            <h1>{!! $fields->first()->getValue() !!}</h1>

            <a data-sidebar-trigger="{{ $tag ?: '' }}" href="{{ $manager->route('fields-edit', $model, $tag) }}" class="mt-3 link link-primary">
                <x-chief-icon-label type="edit"></x-chief-icon-label>
            </a>
        </div>
    @elseif(count($fields) > 0)
        <x-chief-card
                class="{{ isset($class) ? $class : '' }}"
                title="{{ $title ?? null }}"
                :editRequestUrl="$manager->route('fields-edit', $model, $tag)"
                sidebarTrigger="data-sidebar-trigger={{ $tag }}"
        >
            <div class="row-start-start gutter-3">
                @foreach($fields->all() as $field)
                    <div class="{{ $field->getWidthStyle() }} space-y-2">
                        <span class="text-xs font-semibold uppercase text-grey-700">
                            {{ ucfirst($field->getLabel()) }}
                        </span>
                        {!! $field->renderOnPage() !!}
                    </div>
                @endforeach
            </div>
        </x-chief-card>
    @endif
</div>
