<div class="section form-group mb30 clearfix">
    <label for="edit-type" class="mb5 col-lg-6">Type melding
        <p class="subtle">Een algemene melding wordt éénmalig per bezoeker getoond. Betalings- of Leveringsmeldingen komen permanent op de resp. checkout pagina's te staan.</p>
    </label>
    <div class="col-lg-6 pn {{ $errors->first('type') ? 'has-error' : null }}">
        <select name="type" id="edit-type" class="form-control">
            @foreach($note::$typeMapping as $key => $value)
                <?php $selected = (old('type',$formValues->type()) == $key); ?>
                <option {{ ($selected ? 'selected="true"' : null) }} value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>

        <div class="mt5">
            @foreach($note::$levelMapping as $key => $value)

                <?php
                    $bootstrapKey = $key;
                    if($key == 'error') $bootstrapKey = 'danger';

                    $selected = (old('level',$formValues->level()) == $key);
                ?>

                <input {{ ($selected ? 'checked="checked"' : null) }} type="radio" name="level" id="level-{{$key}}" value="{{ $key }}">
                <span class="label label-{{$bootstrapKey}} label-xs mr10">{{ $value }}</span>
            @endforeach
        </div>

        {!! $errors->first('type', '<div class="subtle-remark text-danger">:message</div>') !!}
    </div>
</div>




<div class="section form-group mb30 clearfix">
    <label for="edit-content" class="mb5 col-lg-6 text-left">
        Tekst
        <p class="subtle mt5">Tekst in de notificatie die wordt getoond aan de bezoeker. Hou deze kort en duidelijk.</p>
    </label>

    <div class="col-lg-6 pn">
        <div class="input-group {{ $errors->first('trans.nl.content') ? 'has-error' : null }}">
            <span class="input-group-addon">nl</span>
            <input type="text" id="edit-content" name="trans[nl][content]" value="{{ old('trans.nl.content',$formValues->trans('nl','content')) }}" class="gui-input">
        </div>
        {!! $errors->first('trans.nl.content', '<div class="subtle-remark mb5 text-danger">:message</div>') !!}

        <div class="mt5 input-group {{ $errors->first('trans.en.content') ? 'has-error' : null }}">
            <span class="input-group-addon">en</span>
            <input type="text" name="trans[en][content]" value="{{ old('trans.en.content',$formValues->trans('en','content')) }}" class="gui-input">
        </div>
        {!! $errors->first('trans.en.content', '<div class="subtle-remark text-danger mb5">:message</div>') !!}

        <div class="mt5 input-group {{ $errors->first('trans.fr.content') ? 'has-error' : null }}">
            <span class="input-group-addon">fr</span>
            <input type="text" name="trans[fr][content]" value="{{ old('trans.fr.content',$formValues->trans('fr','content')) }}" class="gui-input">
        </div>
        {!! $errors->first('trans.fr.content', '<div class="subtle-remark text-danger">:message</div>') !!}
    </div>
</div>

<div class="form-group mb30 clearfix">
    <label for="start-at-datepicker" class="mb5 col-lg-6 text-left">Bepaalde periode
        <p class="subtle mt5">Enkel binnen deze periode zal de melding zichtbaar zijn.</p>
    </label>
    <div class="col-lg-6">
        <div class="row">
            <div class="section col-md-5 pn {{ $errors->first('start_at') ? 'has-error' : null }}">
                <div class="field">
                    <input type="date" id="start-at-datepicker" name="start_at" value="{{ old('start_at',$formValues->startAt()) }}" class="gui-input hasDatepicker" placeholder="t.e.m.">
                </div>
                {!! $errors->first('start_at', '<div class="subtle-remark text-danger">:message</div>') !!}
            </div>
            <div class="col-md-2 hidden-sm mt10 text-center">t.e.m.</div>
            <div class="section col-md-5 pn {{ $errors->first('end_at') ? 'has-error' : null }}">
                <div class="field">
                    <input type="date" id="end-at-datepicker" name="end_at" value="{{ old('end_at',$formValues->endAt()) }}" class="gui-input hasDatepicker" placeholder="t.e.m.">
                </div>
                {!! $errors->first('end_at', '<div class="subtle-remark text-danger">:message</div>') !!}
            </div>
        </div>
    </div>
</div>