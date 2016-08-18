@extends('admin._layouts.master')

@section('page-title')
    Jobs
@stop

@section('topbar-right')
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new job</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th>Title</th>
                <th>Translations</th>
                <th>Online</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($jobs as $job)

                <tr>
                    <td>
                        <a href="{{ route('admin.jobs.edit',$job->getKey()) }}">
                            @foreach($job->getUsedLocales() as $usedLocale)
                                {{ $job->getTranslation($usedLocale)->title }}
                            @endforeach
                        </a>
                    </td>
                    <td>{!! implode(', ',$job->getUsedLocales()) !!}</td>
                    <td>
                        <div class="switch switch-success round switch-inline">
                            {!! Form::checkbox('published',1,$job->isPublished(),['data-publish-toggle'=>$job->job_id,'id' => "switch{$job->job_id}"]) !!}
                            <label title="{{ $job->isPublished()?'Online':'Offline' }}" for="switch{{$job->job_id}}"></label>
                        </div>
                    </td>

                    <td class="text-right">
                        <a title="View {{ $job->title }} on site" href="{{ route('pages.carriere') }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $job->title }}" href="{{ route('admin.jobs.edit',$job->getKey()) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
@stop


@section('custom-scripts')
    <script>
        jQuery(document).ready(function ($) {

            var $triggers = $('[data-publish-toggle]'),
                    url = "{{route('admin.jobs.publish')}}"

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
                       console.log(data);
                        var title =  data.published ? 'online' : 'offline';
                        $this.parent().find('label').prop('title', title);
                    }
                });
            });
        });
    </script>
@stop