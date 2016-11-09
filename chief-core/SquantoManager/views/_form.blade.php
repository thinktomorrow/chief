<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale.'-'.$line->id}}-inputValue">
        {{ $line->label }}

        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('back.squanto.lines.edit',$line->id) }}" class="subtle">{{ $line->key }}</a>
        @endif

    </label>
    <div class="col-lg-8 bs-component">

        @if($line->editInEditor())
            {!! Form::textarea('trans['.$locale.']['.$line->id.']',old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)),['id' => $locale.'-'.$line->id.'-inputValue','class' => 'form-control redactor-editor','rows' => 5]) !!}
        @elseif($line->editInTextarea())
            {!! Form::textarea('trans['.$locale.']['.$line->id.']',old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)),['id' => $locale.'-'.$line->id.'-inputValue','class' => 'form-control','rows' => 5]) !!}
        @else
            {!! Form::text('trans['.$locale.']['.$line->id.']',old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)),['id' => $locale.'-'.$line->id.'-inputValue','class' =>'form-control']) !!}
        @endif

        @if($line->description)
            <p class="subtle">{{ $line->description }}</p>
        @endif
    </div>
</div>
