@extends('admin._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/redactor/redactor.css') }}">
@stop

@section('custom-scripts')
    <script src="{{ asset('assets/admin/vendor/redactor/redactor.js') }}"></script>
    <script>
        ;(function ($) {

            $('.redactor-editor').redactor({
                focus: true,
                pastePlainText: true,
                buttons: ['html','formatting','bold','italic',
                    'unorderedlist','orderedlist','outdent','indent',
                    'link','alignment']
            });

        })(jQuery);
    </script>

@stop

@section('page-title','Add new translation line')

@section('content')

    {!! Form::model($trans,['method' => 'POST', 'route' => ['admin.trans.lines.store',$group->slug],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
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
                            {!! Form::text('label',null,['id' => 'inputDescription','class' =>'form-control']) !!}
                            <span class="subtle">Optional information for webmaster (only shown in admin)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-8 col-lg-offset-3 bs-component">
                            {!! Form::checkbox('paragraph',1,old('paragraph',$trans->isParagraph()),['id' => 'inputParagraph']) !!}
                            <label for="inputParagraph">Optimize the inputfield for multiple lines of text</label>
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
                                {{ $locale }}
                            </label>
                            <div class="col-lg-8 bs-component">

                                @if(!$trans->isParagraph())
                                    {!! Form::text('trans['.$locale.']',old('trans['.$locale.']',($trans->getTranslation($locale,false) ? $trans->getTranslation($locale,false)->value : null)),['id' => $locale.'-inputValue','class' =>'form-control']) !!}
                                @else
                                    {!! Form::textarea('trans['.$locale.']',old('trans['.$locale.']',($trans->getTranslation($locale,false) ? $trans->getTranslation($locale,false)->value : null)),['id' => $locale.'-inputValue','class' => 'form-control','rows' => 5]) !!}
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
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Add translation line</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-faq-toggle" href="{{ URL::previous() }}"> cancel</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

