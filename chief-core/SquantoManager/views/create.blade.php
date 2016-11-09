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

    })(jQuery);
</script>
@endpush

@section('page-title','Add new translation key')

@section('content')

    {!! Form::model($line,['method' => 'POST', 'route' => ['back.squanto.lines.store'],'files' => false,'role' => 'form','class'=>'form-horizontal']) !!}
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
                            <span class="subtle">Unique identifier for usage in your view files. e.g. about.button.label or homepage.intro.text. The first segment of this key determines the page where this element will be stored under.</span>
                        </div>
                    </div>

                    <hr>

                    @foreach($available_locales as $locale)
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="{{$locale}}-inputValue">
                                {{ $locale }} value
                            </label>
                            <div class="col-lg-8 bs-component">
                                {!! Form::textarea('trans['.$locale.']',old('trans['.$locale.']',$line->getValue($locale,false)),['id' => $locale.'-inputValue','class' => 'form-control','rows' => 5]) !!}
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

