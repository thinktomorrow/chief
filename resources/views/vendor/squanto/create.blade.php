@extends('chief::back._layouts.master')

@section('page-title','Add new translation key')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Nieuwe vertaling toevoegen')
@endcomponent

@section('content')

    <form method="POST" action="{{ route('squanto.lines.store') }}" role="form" class="form-horizontal">
        {{ csrf_field() }}

        <section class="formgroup">
            <div class="row gutter-xl">
                <div class="formgroup-info column-5">
                    <h2 class="formgroup-label">Key</h2>
                    <p>
                        <span class="subtle">Unique identifier for usage in your view files. e.g. about.button.label or homepage.intro.text. The first segment of this key determines the page where this element will be stored under.</span>
                    </p>
                </div>
                <div class="formgroup-input column-7">

                    <div class="formgroup-input">
                        <input name="key" type="text" value="">
                    </div>

                </div>
            </div>
        </section>

            @foreach($available_locales as $locale)

                <section class="formgroup">
                    <div class="row gutter-xl">
                        <div class="formgroup-info column-5">
                            <h2 class="formgroup-label">{{ $locale }} value</h2>
                        </div>
                        <div class="formgroup-input column-7">

                            <div class="formgroup-input">
                                <textarea name="trans[{{ $locale }}]" id="{{ $locale }}-inputValue" class="form-control" rows="5">{!! old('trans['.$locale.']',$line->getValue($locale,false)) !!}</textarea>
                            </div>

                        </div>
                    </div>
                </section>
            @endforeach

            <div class="text-right inline-group-s">
                <a class="subtle" id="remove-faq-toggle" href="{{ URL::previous() }}"> cancel</a>
                <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Add translation line</button>
            </div>
    </form>

@stop
