@extends('chief::back._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('chief-assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
<script src="{{ asset('chief-assets/back/vendor/redactor/redactor.js') }}"></script>
<script>
    ;(function ($) {

        $('.redactor-editor').redactor({
            focus: false,
            pastePlainText: true,
            buttons: ['html', 'formatting', 'bold', 'italic',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'link', 'alignment','image','horizontalrule'],
        });

    })(jQuery);
</script>
@endpush

@section('page-title','Edit line')

@component('chief::back._layouts._partials.header')
    @slot('title', $line->label. ' bewerken')
@endcomponent

@section('content')

    <form method="POST" action="{{ route('squanto.lines.update',$line->id) }}" role="form" class="form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2>Key</h2>
                    <p>
                        <span class="subtle">Unique identifier for usage in your view files. e.g. about.button.label or homepage.intro.text. The first segment of this key determines the page where this element will be stored under.</span>
                    </p>
                </div>
                <div class="formgroup-input column-7">

                    <div class="formgroup-input">
                        <input name="key" type="text" value="{{ old('key',$line->key) }}">
                    </div>

                </div>
            </div>
        </section>

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2>Label</h2>
                    <p>
                        <span class="subtle">Benaming van deze vertaling en optionele extra omschrijving van de vertaallijn. (enkel zichtbaar in admin)</span>
                    </p>
                </div>
                <div class="formgroup-input column-7">

                    <div class="formgroup-input">
                        <input class="stack-s" name="label" type="text" value="{{ old('label',$line->label) }}">
                        <textarea name="description">{{ old('description',$line->description) }}</textarea>
                    </div>

                </div>
            </div>
        </section>

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2>Fieldtype</h2>
                    <p></p>
                </div>
                <div class="formgroup-input column-7">

                        <label class="stack-xs custom-indicators" for="typeText">
                            <input {{ old('type',$line->editInTextInput()) == 1 ? 'checked="checked"':'' }} name="type" value="1" id="typeText" type="radio">
                            <span class="custom-radiobutton --primary"></span>
                            Text (default)
                        </label>
                        <label class="stack-xs custom-indicators" for="typeTextarea">
                            <input {{ old('type',$line->editInTextarea()) == 1 ? 'checked="checked"':'' }} name="type" value="2" id="typeTextarea" type="radio">
                            <span class="custom-radiobutton --primary"></span>
                            textarea (plain text)
                        </label>
                        <label class="stack-xs custom-indicators" for="typeEditor">
                            <input {{ old('type',$line->editInEditor()) == 1 ? 'checked="checked"':'' }} name="type" value="3" id="typeEditor" type="radio">
                            <span class="custom-radiobutton --primary"></span>
                            editor (html)
                        </label>


                </div>
            </div>
        </section>

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2>Fieldtype</h2>
                    <p></p>
                </div>
                <div class="formgroup-input column-7">

                        @foreach($available_locales as $locale)
                            <div class="stack">
                                <label for="{{$locale}}-inputValue">
                                    {{ $locale }} value
                                </label>
                                @if($line->editInEditor())
                                    <textarea name="trans[{{ $locale }}]" id="{{ $locale }}-inputValue" class="redactor-editor" rows="5">{!! old('trans['.$locale.']',$line->getValue($locale,false)) !!}</textarea>
                                @elseif($line->editInTextarea())
                                    <textarea name="trans[{{ $locale }}]" id="{{ $locale }}-inputValue" class="" rows="5">{!! old('trans['.$locale.']',$line->getValue($locale,false)) !!}</textarea>
                                @else
                                    <input type="text" name="trans[{{ $locale }}]" id="{{ $locale }}-inputValue" class="" value="{!! old('trans['.$locale.']',$line->getValue($locale,false)) !!}"/>
                                @endif
                            </div>
                        @endforeach


                </div>
            </div>
        </section>

        <div class="text-right inline-group-s">
            <delete url="{{ route('squanto.lines.destroy',$line->id) }}" :modal="true" title="Vertaling permanent verwijderen">
                <span slot="modalBtn" class="btn btn-o-subtle">
                    <span class="icon icon-trash"></span>
                    Permanent verwijderen
                </span>
                <p slot="message">
                    Hou er rekening mee dat alle toegepaste vertalingen van de site zullen verdwijnen.
                </p>
            </delete>
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Bewaar aanpassingen</button>
        </div>

    </form>

@stop
