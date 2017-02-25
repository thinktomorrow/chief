@extends('back._layouts.master')

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor/redactor.css') }}">
@stop

@push('custom-scripts')
    <script src="{{ asset('assets/back/vendor/redactor/redactor.js') }}"></script>
    <script>
        ;(function ($) {

            $('.redactor-editor').redactor({
                focus: true,
                pastePlainText: true,
                buttons: ['html', 'formatting', 'bold', 'italic',
                    'unorderedlist', 'orderedlist', 'outdent', 'indent',
                    'link', 'alignment','image','horizontalrule'],
                imageUpload: '{{ route('back.articles.fileupload') }}?id={{ $article->id }}&_token={{ csrf_token() }}',
                image_dir: '{{ $article::getContentImageDirectory() }}',
                imageUploadErrorCallback: function(json)
                {
                    $('body').prepend('<div class="alert alert-top alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><div class="container">'+json.message+'</div></div>');
                }
            });

            // Delete modal
            $("#remove-article-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

            // Initiate our cropper
            new Cropper();

        })(jQuery);
    </script>

@endpush

@section('page-title','Article: '.$article->title)

@section('topbar-right')
    <a type="button" href="{{ route('articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> Bekijk op de site</a>
@stop

@section('content')

    {!! Form::model($article,['method' => 'PUT', 'route' => ['back.articles.update',$article->getKey()],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('back.articles._formtabs')

        <div class="col-md-3">

            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Sla veranderingen op</button>
                </div>
                <div class="text-center">
                    <span class="subtle">Laatst geupdate op: {{ $article->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="text-center">
                    <a class="subtle subtle-danger" id="remove-article-toggle" href="#remove-article-modal"><i class="fa fa-remove"></i> Verwijder dit artikel?</a>
                </div>
            </div>

            <hr class="xsmall">

            <div class="form-group text-center">

                <div>
                    <label class="control-label subtle" for="inputPublished">Zet artikel publiek</label>
                </div>

                <div class="switch switch-success round switch-inline">
                    {!! Form::checkbox('published',1,$article->isPublished(),['id' => "inputPublished"]) !!}
                    <label title="{{ $article->isPublished()?'Online':'Offline' }}" for="inputPublished"></label>
                </div>

            </div>

            <hr class="xsmall">

            <div class="form-group">

                @include('back.articles._fileupload')
                
            </div>

            <div class="form-group">
                <label class="control-label" for="inputService">Positie op het nieuwsoverzicht:</label>
                <i class="fa fa-question-circle" data-toggle="tooltip" title="Sleep en log je pagina naar zijn nieuwe plaats."></i>
                <div class="bs-component">
                    <ul class="list-group sortable">

                        @foreach(Chief\Articles\Article::sequence()->get() as $sibling)

                            <?php $current = ($sibling->id === $article->id) ? ' current' : null; ?>

                            <li class="list-group-item{{$current}}">
                                <input type="hidden" name="sequence[]" value="{{ $sibling->id }}">
                                <span title="{{ $sibling->title }}">{{ teaser($sibling->title,36,'...') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('back.articles._deletemodal')

@stop

