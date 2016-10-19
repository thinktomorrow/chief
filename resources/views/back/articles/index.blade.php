@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@section('topbar-right')
    <a href="{{ route('back.articles.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> voeg een artikel toe</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th></th>
                <th>Titel</th>
                <th>Fragment</th>
                <th>Aangepast</th>
                <th>Online</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($articles as $article)
                <tr>
                    <td style="width:6%">
                        @if ($article->hasThumb())
                            <img class="img-responsive rounded" src="{!! $article->getThumbUrl() !!}" alt="Thumb">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('back.articles.edit',$article->getKey()) }}">
                            @foreach($article->getUsedLocales() as $usedLocale)
                                {{ $article->getTranslationFor('title',$usedLocale) }}
                            @endforeach
                        </a>
                    </td>
                    <td class="subtle">
                        {{ teaser($article->content,400,'...') }}
                    </td>
                    <td class="subtle">
                        {{ $article->updated_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        <div class="switch switch-success round switch-inline">
                            {!! Form::checkbox('published',1,$article->isPublished(),['data-publish-toggle'=>$article->id,'id' => "switch{$article->id}"]) !!}
                            <label title="{{ $article->isPublished()?'Online':'Offline' }}" for="switch{{$article->id}}"></label>
                        </div>
                    </td>

                    <td style="width:10%;" class="text-right">
                        <a title="View {{ $article->title }} on site" href="{{ route('articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $article->title }}" href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center">
        {!!  $articles->render() !!}
    </div>
@stop


@push('custom-scripts')
    <script>
        jQuery(document).ready(function ($) {

            var $triggers = $('[data-publish-toggle]'),
                    url = "{{route('back.articles.publish')}}"

            $triggers.on('click', function () {
                var $this = $(this);

                $.ajax({
                    data: {
                        id: $this.data('publish-toggle'),
                        checkboxStatus: this.checked,
                        _token: '{!! csrf_token() !!}'
                    },
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        var title =  data.published ? 'online' : 'offline';
                        $this.parent().find('label').prop('title', title);
                    }
                });
            });
        });
    </script>
@endpush