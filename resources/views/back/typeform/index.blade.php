@extends('admin._layouts.master')

@section('page-title')
    {{ count($entries) }} Typeform entries <a type="button" href="{{ route('admin.typeform.download') }}" class="btn btn-rounded btn-default btn-xs">Download .csv</a>
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th style="width:25%;">Date submitted</th>
	            <th>Company Name</th>
                <th>Contact Name</th>
	            <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($entries as $entry)
				@if($newEntriesCount >0)
                    <tr class="success">
                @else
					<tr>
                @endif
                    <td>
                        {{ $entry->submit_date }}
                    </td>
	                <td>{{!$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23540670")->isEmpty()?$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23540670")->first()->answer:$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23123887")->first()->answer}}</td>
					<td>{{!$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23540669")->isEmpty()?$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23540669")->first()->answer:$entry->typeformAnswer->where('typeformQuestion.field_id',"textfield_23124015")->first()->answer}}</td>
					<td><a type="button" href="{{ route('admin.typeform.entry',$entry->id) }}" class="btn btn-rounded btn-default btn-xs">View answers</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

	{!! $entries->render() !!}

@stop
