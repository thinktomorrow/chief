{{-- note: locale must be passed to this form which makes each formtab unique --}}
<div class="form-group">
    <label class="col-lg-3 control-label" for="inputName">Name</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('name',null,['id' => 'inputName','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputCompany">Company</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('company',null,['id' => 'inputCompany','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputFunction">Function</label>
    <div class="col-lg-8 bs-component">
        {!! Form::text('function',null,['id' => 'inputFunction','class' =>'form-control']) !!}
    </div>
</div>

<hr>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputTitle">Title</label>
    <div class="col-lg-6 bs-component">
        {!! Form::text('title',null,['id' => 'inputTitle','class' =>'form-control']) !!}
    </div>
    <div class="col-lg-2 bs-component">
        {!! Form::select('locale',\BNP\Locale\Locale::getForSelect(),null,['id' => 'inputLocale','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputDescription">Testimonial</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('content',null,['id' => 'inputDescription','class' => 'form-control redactor-editor']) !!}
    </div>
</div>
