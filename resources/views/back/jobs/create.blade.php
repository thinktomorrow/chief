@extends('admin._layouts.master')

@section('page-title','Add new job offer')

@section('content')

    {!! Form::model($job,['method' => 'POST', 'route' => ['admin.jobs.store'],'files' => true,'role' => 'form','class'=>'form-horizontal']) !!}
    <div class="row">

        @include('admin.jobs._formtabs')

        <div class="col-md-3">
            <div class="form-group">
                <div class="bs-component text-center">
                    <button class="btn btn-success btn-lg" type="submit"><i class="fa fa-check"></i> Create job</button>
                </div>
                <div class="text-center">
                    <a class="subtle" id="remove-job-toggle" href="{{ URL::previous() }}"> cancel</a>
                </div>
            </div>
        </div><!-- end sidebar column -->
    </div>
    {!! Form::close() !!}

@stop

