@unless($inlineEdit)
    <div data-fields-component="{{ $componentKey }}" data-{{ $componentKey }}-component>
        @if(public_method_exists($model, 'render'.ucfirst($componentKey).'Component'))
            {!! $model->{'render'.ucfirst($componentKey).'Component'}() !!}
        @elseif(isset($template))
            @include($template)
        @else
            @include('chief::manager.windows.fields.templates.default')
        @endif
    </div>
@else
    <div>
        @include('chief::manager.windows.fields.templates.inline-edit')
    </div>
@endunless
