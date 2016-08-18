@extends('admin._layouts.master')

@section('page-title')
    Faqs
@stop

@section('topbar-right')
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new faq</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th>Title</th>
                <th>Excerpt</th>
                <th></th>
                <th style="width:9%;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($faqs as $faq)

                <tr>
                    <td>
                        <a href="{{ route('admin.faqs.edit',$faq->id) }}">
                            {{ $faq->title }}
                        </a>
                    </td>
                    <td class="subtle">
                        {{ teaser($faq->content,400,'...') }}
                    </td>
                    <td>{!! implode(', ',$faq->getUsedLocales()) !!}</td>
                    <td class="text-right">
                        <a title="View {{ $faq->title }} on site" href="{{ route('pages.faq') }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $faq->title }}" href="{{ route('admin.faqs.edit',$faq->id) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@stop