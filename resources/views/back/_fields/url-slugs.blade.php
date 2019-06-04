<section class="row formgroup stack gutter-l bg-white">
    <div class="column-4">
        <h2 class="formgroup-label">Link naar de pagina</h2>
        <p>Bepaal hier welke link er gebruikt wordt voor deze pagina.</p>
        <p>
            {{--@foreach($fields->urlPreviews() as $urlPreview)--}}
                {{--<span>--}}
                    {{--<span>{{ $urlPreview->prepend }}</span>--}}
                    {{--<span>{{ $urlPreview->slug }}</span>--}}
                {{--</span>--}}
            {{--@endforeach--}}
        </p>
    </div>
    <div class="formgroup-input column-8">

        @foreach($fields->all() as $field)
            <label for="{{ $field->key }}">{{ $field->label }}</label>
            <div class="input-addon stack-xs">
                @if($field->prepend)
                    <div class="addon inset-s">{{ $field->prepend }}</div>
                @endif
                <input type="text" name="{{ $field->name }}" id="{{ $field->key }}" class="input inset-s" placeholder="{{ $field->placeholder ?? '' }}" value="{{ $field->value() }}">
            </div>
            @if($field->description)
                <p>{!! $field->description !!}</p>
            @endif
        @endforeach
        <error class="caption text-warning" field="url-slugs" :errors="errors.all()"></error>

        <h3>De gekozen link bestaat reeds!</h3>
            <input type="text">
        <button>aanpassen</button>

    </div>
</section>
