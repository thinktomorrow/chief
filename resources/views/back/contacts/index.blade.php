@extends('admin._layouts.master')

@section('page-title')
    {{ count($contacts) }} contacts
@stop

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th style="width:10%;">Date</th>
                <th style="width:25%;">Person</th>
                <th>Message</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contacts as $contact)

                <tr>
                    <td class="subtle">
                        {{ $contact->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        {{ $contact->firstname }} {{ $contact->name }}
                        <br>
                        <span class="subtle"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> - {{ $contact->company }}</span>
                    </td>
                    <td class="subtle">
                        <strong>{{ $contact->subject }}</strong><br>
                        {!! nl2br(strip_tags($contact->content)) !!}
                    </td>

                </tr>

            @endforeach
            </tbody>
        </table>
    </div>


@stop