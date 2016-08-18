@extends('admin._layouts.master')

@section('page-title')
    Offices
@stop

@section('topbar-right')
    <a href="{{ route('admin.offices.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new office</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th style="width:25%;">Country</th>
                <th>Title</th>
                <th>Excerpt</th>
                <th style="width:9%;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($offices as $office)

                <tr>
                    <td style="width:6%">
                        {{ $office->country_key }}
                    </td>
                    <td>
                        <a href="{{ route('admin.offices.edit',$office->id) }}">
                            {{ $office->title }}
                        </a>
                    </td>
                    <td class="subtle">
                        {{ teaser($office->content,400,'...') }}
                    </td>
                    <td class="text-right">
                        <a title="View {{ $office->title }} on site" href="{{ route('pages.home') }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $office->title }}" href="{{ route('admin.offices.edit',$office->id) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@stop