@foreach($lines as $line)
    <div class="form-group">
        <label class="col-lg-3 control-label" for="{{$locale.'-'.$line->id}}-inputValue">
            {{ $line->label }}
            <span class="subtle">{{ $line->key }}</span>
        </label>
        <div class="col-lg-8 bs-component">

            @if(!$line->isParagraph())
                {!! Form::text('trans['.$locale.']['.$line->id.']',old('trans['.$locale.']['.$line->id.']',($line->getTranslation($locale,false) ? $line->getTranslation($locale,false)->value : null)),['id' => $locale.'-'.$line->id.'-inputValue','class' =>'form-control']) !!}
            @else
                {!! Form::textarea('trans['.$locale.']['.$line->id.']',old('trans['.$locale.']['.$line->id.']',($line->getTranslation($locale,false) ? $line->getTranslation($locale,false)->value : null)),['id' => $locale.'-'.$line->id.'-inputValue','class' => 'form-control','rows' => 5]) !!}
            @endif

            @if($line->description)
                <p class="subtle">{{ $line->description }}</p>
            @endif
        </div>
    </div>
@endforeach
