<div class="form-group">
    <label class="col-lg-3 control-label" for="inputTitle">Office</label>
    <div class="col-lg-6 bs-component">
        {!! Form::text('title',null,['id' => 'inputTitle','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputCountry">Country</label>
    <div class="col-lg-8 bs-component">
        {!! Form::select('country_key',\BNP\Offices\Country::getForSelect(),null,['id' => 'inputCountry','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-3 control-label" for="inputDescription">Contact details</label>
    <div class="col-lg-8 bs-component">
        {!! Form::textarea('content',null,['id' => 'inputDescription','class' => 'form-control redactor-editor']) !!}
    </div>
</div>
