@extends('admin._layouts.master')

@section('page-title')
    Factoring pages
@stop

@section('topbar-right')
    <a href="{{ route('admin.services.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new page</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th>Title</th>
                <th>Translations</th>
                <th>Modules</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($services as $service)

                <tr>
                    <td>
                        <a href="{{ route('admin.services.edit',$service->id) }}">
                            {{ $service->title }}
                        </a>
                    </td>

                    <td>{!! implode(', ',$service->getUsedLocales()) !!}</td>
                    <td>
                        <ul class="list-unstyled">
                            @foreach($service->modules as $module)
                                <li><a class="subtle" href="{{ route('admin.modules.edit',$module->id) }}">{{ $module->strippedTitle }}</a></li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="text-right">
                        <a title="View {{ $service->title }} on site" href="{{ route('services.show',$service->slug) }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $service->title }}" href="{{ route('admin.services.edit',$service->id) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@stop