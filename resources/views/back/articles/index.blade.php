@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@section('topbar-right')
    <a href="{{ route('articles.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> voeg een artikel toe</a>
    <form action="{{ route('articles.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="image">
        <button type="submit">Upload</button>
    </form>
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
            <img src="{{ $articlesMedia->getMedia()[1]->getUrl('thumb') }}" alt="">
            {{--<div>{{ $articlesMedia->getMedia('images')[0]->getPath('icon') }}</div>--}}
            {{--<div>{{ $articlesMedia->getMedia('images')[0]->getPath('thumb') }}</div>--}}
            {{--@foreach($articlesMedia as $article)--}}
                {{--<tr>--}}
                    {{--<td>--}}
                        {{--{{ $article->collection_name }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--{{ $article->disk }}--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<img src="{{ $article->getUrl('thumb') }}" alt="">--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<img src="{{ $article->getUrl('icon') }}" alt="">--}}
                    {{--</td>--}}
                {{--</tr>--}}

            {{--@endforeach--}}
            </tbody>
        </table>
    </div>

@stop


@push('custom-scripts')
    <script>

    </script>
@endpush
