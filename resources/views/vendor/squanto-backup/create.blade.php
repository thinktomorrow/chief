@extends('chief::layout.master')

@section('page-title','Nieuwe vertaling toevoegen')

@component('chief::layout._partials.header')
    @slot('title', 'Nieuwe vertaling toevoegen')
@endcomponent

@section('content')

    <form method="POST" action="{{ route('squanto.lines.store') }}" role="form">
        {{ csrf_field() }}

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2>Key</h2>
                        <span class="subtle">Unique identifier for usage in your view files. e.g. about.button.label or homepage.intro.text. The first segment of this key determines the page where this element will be stored under.</span>
                </div>
                <div class="formgroup-input column-7">

                    <div class="formgroup-input">
                        <input class="input inset-s" name="key" type="text" value="{{ old('key') }}">
                    </div>

                </div>
            </div>
        </section>

            @foreach($available_locales as $locale)

                <section class="formgroup">
                    <div class="row gutter-xl">
                        <div class="formgroup-info column-5">
                            <h2>{{ $locale }} value</h2>
                        </div>
                        <div class="formgroup-input column-7">

                            <div class="formgroup-input">
                                <textarea name="trans[{{ $locale }}]" id="{{ $locale }}-inputValue" class="input inset-s" rows="5">{!! old('trans['.$locale.']',$line->getValue($locale,false)) !!}</textarea>
                            </div>

                        </div>
                    </div>
                </section>
            @endforeach

            <div class="stack text-right inline-group-s">
                <a class="subtle" id="remove-faq-toggle" href="{{ URL::previous() }}"> cancel</a>
                <button class="btn btn-primary btn-lg" type="submit"> Add translation line</button>
            </div>
    </form>

@stop
