@extends('admin._layouts.master')

@section('custom-scripts')
    <script>
        ;(function ($) {

            // Delete modal
            $("#remove-job-toggle").magnificPopup();

            // Sortable
            var el = document.getElementsByClassName('sortable')[0];
            var sortable = Sortable.create(el);

        })(jQuery);
    </script>

@stop

@section('page-title','Job: '.$job->title)

@section('topbar-right')
    <a type="button" href="{{ route('pages.carriere') }}" target="_blank" class="btn btn-default btn-sm btn-rounded"><i class="fa fa-eye"></i> View on site</a>
@stop

@section('content')

    {!! Form::model($job,['method' => 'PUT', 'route' => ['admin.jobs.update',$job->getKey()],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.jobs._formtabs')

        <div class="col-md-3">

            <div class="form-group">
                <p class="subtle">Either a link or a pdf document can be uploaded. This can be set per language.</p>
            </div>

            <div class="form-group">
                <label class="control-label" for="inputJob">Position on site:</label>
                <i class="fa fa-question-circle" data-toggle="tooltip" title="Drag and drop your page to its new position."></i>
                <div class="bs-component">
                    <ul class="list-group sortable">

                        @foreach(\BNP\Jobs\Job::getAll() as $sibling)

                            <?php $current = ($sibling->getKey() === $job->getKey()) ? ' current' : null; ?>

                            <li class="list-group-item{{$current}}">
                                <input type="hidden" name="sequence[]" value="{{ $sibling->getKey() }}">
                                <span title="{{ $sibling->title }}">{{ teaser($sibling->title,36,'...') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="form-group">
                <div class="bs-component">
                    {!! Form::checkbox('published',1,$job->isPublished(),['id' => 'inputPublished']) !!}
                    <label class="control-label" for="inputPublished">Publish job</label>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Save your changes</button>
                </div>
                <div class="text-center">
                    <span class="subtle">Last updated on: {{ $job->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="text-center">
                    <a class="subtle subtle-danger" id="remove-job-toggle" href="#remove-job-modal"><i class="fa fa-remove"></i> remove this job?</a>
                </div>

            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

    @include('admin.jobs._deletemodal')

@stop

