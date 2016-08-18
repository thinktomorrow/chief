@extends('admin._layouts.master')

@section('page-title')
    Testimonials
@stop

@section('topbar-right')
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-success btn-sm btn-rounded"><i class="fa fa-plus"></i> add a new testimonial</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th></th>
                <th style="width:25%;">Person</th>
                <th>Title</th>
                <th>Excerpt</th>
                <th style="width:10%;">Display <i class="fa fa-question-circle" data-toggle="tooltip" title="Is this testimonial displayed on a separate page or as a popup."></i></th>
                <th>Online</th>
                <th style="width:9%;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($testimonials as $testimonial)

                <tr>
                    <td style="width:6%">
                        @if ($testimonial->hasImage())
                            <img class="img-responsive" src="{!! $testimonial->getImageUrl() !!}" alt="{{ $testimonial->name }}">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit',$testimonial->id) }}">
                            {{ $testimonial->name }}
                        </a>
                        <br>
                        <span class="subtle">{{ $testimonial->function }} @ {{ $testimonial->company }}</span>
                    </td>
                    <td class="subtle">
                        {{ $testimonial->title }}
                    </td>
                    <td class="subtle">
                        {{ teaser($testimonial->content,400,'...') }}
                    </td>
                    <td>
                        <span class="badge">{{ $testimonial->locale }}</span>

                        @if($testimonial->showOnFullpage())
                            <span class="badge">page</span>
                        @else
                            <span class="badge">popup</span>
                        @endif
                    </td>

                    <td>
                        <div class="switch switch-success round switch-inline">
                            {!! Form::checkbox('published',1,$testimonial->isPublished(),['data-publish-toggle'=>$testimonial->id,'id' => "switch{$testimonial->id}"]) !!}
                            <label title="{{ $testimonial->isPublished()?'Online':'Offline' }}" for="switch{{$testimonial->id}}"></label>
                        </div>
                    </td>

                    <td class="text-right">
                        <a title="View {{ $testimonial->title }} on site" href="{{ route('testimonials.show',$testimonial->slug) }}" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $testimonial->title }}" href="{{ route('admin.testimonials.edit',$testimonial->id) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
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
                url = "{{route('admin.testimonials.publish')}}"

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
@stop