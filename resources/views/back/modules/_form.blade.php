{{-- note: locale must be passed to this form which makes each formtab unique --}}
<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale}}-inputTitle">Title <i class="fa fa-question-circle" data-toggle="tooltip" title="Please use two lines and put main word in bold. The custom display of the sidebar navigation requires you to have this specific markup."></i></label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control redactor-air']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{ $locale }}-inputDescription">Description</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][content]',null,['id' => $locale.'-inputDescription','class' => 'form-control redactor-editor']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{ $locale }}-inputTeaser">Teaser <i class="fa fa-question-circle" data-toggle="tooltip" title="If left blank, the first characters of the content will be displayed."></i></label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][teaser]',null,['id' => $locale.'-inputTeaser','class' => 'form-control redactor-editor']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="{{ $locale }}-inputMetaDescription">SEO description</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('trans['.$locale.'][meta_description]',null,['id' => $locale.'-inputMetaDescription','class' => 'form-control','rows' => '3']) !!}
    </div>
</div>
