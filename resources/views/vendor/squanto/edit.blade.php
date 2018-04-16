@extends(config('squanto.template','back._layouts.master'))

@section('page-title')
    {{ $page->label }}
@stop

@component('back._layouts._partials.header')
    @slot('title', $page->label)
    @if(admin()->isSquantoDeveloper())
        <a href="{{ route('squanto.lines.create', $page->id) }}" class="btn btn-default"><i class="fa fa-plus"></i> add new line</a>
    @endif
    <button data-submit-form="translationForm" class="btn btn-success"><i class="fa fa-check"></i>Wijzigingen opslaan</button>
@endcomponent

@section('content')

    <form id="translationForm" method="POST" action="{{ route('squanto.update',$page->id) }}" role="form" class="form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        @include('squanto::_formtabs')

        <!-- hide form button but keep it so input enters still work for submission of form -->
        <button class="btn btn-success --hidden" type="submit"><i class="fa fa-check"></i>Wijzigingen opslaan</button>

    </form>


@stop
