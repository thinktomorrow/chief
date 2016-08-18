@extends('admin._layouts.master')

@section('page-title')
    Factoring modules
@stop

@section('topbar-right')
    <a href="{{ route('admin.modules.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new module</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form theme-warning fs13">
            <thead>
            <tr class="bg-light">
                <th>Title</th>
                <th>Translations</th>
                <th>Page</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($modules as $module)

                <tr>
                    <td>
                        <a href="{{ route('admin.modules.edit',$module->id) }}">
                            {{ $module->strippedTitle }}
                        </a>
                    </td>

                    <td>{!! implode(', ',$module->getUsedLocales()) !!}</td>
                    <td>
                        @if($service = $module->service)
                            <a class="subtle" href="{{ route('admin.services.edit',$service->id) }}">
                                {{ $service->title }}
                            </a>
                        @else
                            -
                        @endif
                    </td>

                    <td class="text-right">
                        <a title="View {{ $module->strippedTitle }} on site" href="{{ route('modules.show',$module->slug) }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $module->strippedTitle }}" href="{{ route('admin.modules.edit',$module->id) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@stop