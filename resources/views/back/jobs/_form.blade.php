{{-- note: locale must be passed to this form which makes each formtab unique --}}
<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale}}-inputTitle">Title</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale}}-inputUrl">Url</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('trans['.$locale.'][url]',null,['id' => $locale.'-inputUrl','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale}}-inputFile">File</label>
    <div class="col-lg-8 bs-component">

        @if(($_trans = $job->getTranslation($locale,false)) && $_trans->filename)
            <p>Current uploaded file: <a href="{{ $job->getFileUrl($locale) }}" target="_blank">{{ $_trans->filename }}</a></p>
        @endif

        {!! Form::file('trans['.$locale.'][filename]',null,['id' => $locale.'-inputFile','class' =>'form-control']) !!}
    </div>
</div>
