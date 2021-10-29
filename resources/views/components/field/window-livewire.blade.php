@php
    $amountOfFields = count($fields);
@endphp

@if($amountOfFields > 0)
    <div data-fields-component="{{ $tag }}" data-{{ $tag }}-component>
        @if(isset($template))
            @include($template)
        @elseif($tag === 'pagetitle')
            @include('chief::components.field.window_pagetitle')
        @else
            <x-chief::window
                :title="$title"
                :url="$manager->route('fields-edit', $model, $tag)"
                :sidebar="$tag"
            >
                <div class="space-y-4">
                    @foreach($fields->all() as $field)
                        <div class="space-y-2">
                            <span class="text-xs font-semibold uppercase text-grey-700">
                                {{ ucfirst($field->getLabel()) }}
                            </span>

                            {!! $field->renderOnPage() !!}
                        </div>
                    @endforeach
                </div>
            </x-chief::window>
        @endif
    </div>
@endif
