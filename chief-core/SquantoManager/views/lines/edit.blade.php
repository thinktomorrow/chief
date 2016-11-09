@extends('back._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
<script src="{{ asset('assets/back/vendor/redactor/redactor.js') }}"></script>
<script>
    ;(function ($) {

        $('.redactor-editor').redactor({
            focus: false,
            pastePlainText: true,
            buttons: ['html', 'formatting', 'bold', 'italic',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'link', 'alignment','image','horizontalrule'],
        });

        // Delete modal
        $("#remove-line-toggle").magnificPopup();

    })(jQuery);
</script>
@endpush

@section('page-title','Edit line')

@section('content')

    {!! Form::model($line,['method' => 'PUT', 'route' => ['back.squanto.lines.update',$line->id],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        <div class="col-md-9">
            <div class="panel mb25">
                <div class="panel-body">

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="inputKey">
                            Key
                        </label>
                        <div class="col-lg-8 bs-component">
                            {!! Form::text('key',null,['id' => 'inputKey','class' =>'form-control']) !!}
                            <span class="subtle">unique identifier for usage in your view files. e.g. button.label or intro.text</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="inputLabel">
                            Label
                        </label>
                        <div class="col-lg-8 bs-component">
                            {!! Form::text('label',null,['id' => 'inputLabel','class' =>'form-control']) !!}
                            <span class="subtle">Descriptive label (only shown in admin)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="inputDescription">
                            Description
                        </label>
                        <div class="col-lg-8 bs-component">
                            {!! Form::text('description',null,['id' => 'inputDescription','class' =>'form-control']) !!}
                            <span class="subtle">Optional information for webmaster (only shown in admin)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-3 bs-component">
                            {!! Form::radio('type','text',$line->editInTextInput(),['id' => 'inputText']) !!}
                            <label for="inputText">text (default)</label>
                        </div>
                        <div class="col-lg-8 col-lg-offset-3 bs-component">
                            {!! Form::radio('type','textarea',$line->editInTextarea(),['id' => 'inputTextarea']) !!}
                            <label for="inputTextarea">textarea (plain text)</label>
                        </div>
                        <div class="col-lg-8 col-lg-offset-3 bs-component">
                            {!! Form::radio('type','editor',$line->editInEditor(),['id' => 'inputEditor']) !!}
                            <label for="inputEditor">editor (html)</label>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-3 bs-component">
                            <h3>Translations</h3>
                        </div>
                    </div>


                    @foreach($available_locales as $locale)
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="{{$locale}}-inputValue">
                                {{ $locale }} value
                            </label>

                            <div class="col-lg-8 bs-component">

                            @if($line->editInEditor())
                                {!! Form::textarea('trans['.$locale.']',old('trans['.$locale.']',$line->getValue($locale,false)),['id' => $locale.'-inputValue','class' => 'form-control redactor-editor','rows' => 5]) !!}
                            @elseif($line->editInTextarea())
                                {!! Form::textarea('trans['.$locale.']',old('trans['.$locale.']',$line->getValue($locale,false)),['id' => $locale.'-inputValue','class' => 'form-control','rows' => 5]) !!}
                            @else
                                {!! Form::text('trans['.$locale.']',old('trans['.$locale.']',$line->getValue($locale,false)),['id' => $locale.'-inputValue','class' =>'form-control']) !!}
                            @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Bewaar aanpassingen</button>
                </div>
                <div class="text-center">
                    <a class="subtle subtle-danger" id="remove-line-toggle" href="#remove-line-modal"><i class="fa fa-remove"></i> Verwijder deze line?</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('squanto::_deletemodal')

@stop

