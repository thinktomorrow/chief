{{-- note: locale must be passed to this form which makes each formtab unique --}}
<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale}}-inputTitle">Title</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{ $locale }}-inputDescription">Description</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][content]',null,['id' => $locale.'-inputDescription','class' => 'form-control redactor-editor']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{ $locale }}-inputMetaDescription">SEO description</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][meta_description]',null,['id' => $locale.'-inputMetaDescription','class' => 'form-control','rows' => '3']) !!}
    </div>
</div>
