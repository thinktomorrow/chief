@if(count($fields) > 0)
    <div data-fields-component="{{ $tag }}" data-{{ $tag }}-component>
        @if(isset($template))
            @include($template)
        @elseif($tag === 'pagetitle')
            @include('chief::manager.windows.fields._partials.pagetitle')
        @else
            <x-chief-form::window
                :title="$title"
                :url="$manager->route('form-edit', $model, $tag)"
                :sidebar="$tag"
            >
                @if($slot)
                    {!! $slot !!}
                @else
                    <div class="space-y-4">
                        <x-chief::fields :fields="$fields" />
                    </div>
                @endif
            </x-chief-form::window>
        @endif
    </div>
@endif
