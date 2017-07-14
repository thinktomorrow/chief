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
            @foreach($articlesMedia as $article)
                <tr>
                    <td>
                        {{ $article->collection_name }}
                    </td>
                    <td>
                        {{ $article->disk }}
                    </td>
                    <td>
                        <img src="{{ $article->getUrl('thumb') }}" alt="">
                    </td>
                    <td>
                        <img src="{{ $article->getUrl('icon') }}" alt="">
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

@stop


@push('custom-scripts')
    <script>
        jQuery(document).ready(function ($) {

            var $triggers = $('[data-publish-toggle]'),
                    url = "{{route('articles.publish')}}"

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