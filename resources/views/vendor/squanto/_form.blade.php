<div class="form-group">
    <label class="col-lg-3 control-label" for="{{$locale.'-'.$line->id}}-inputValue">
        {{ $line->label }}

        @can('delete-squanto')
            <a href="{{ route('squanto.lines.edit', $line->id) }}" class="subtle">{{ $line->key }}</a>
        @endcan

    </label>
    <div class="col-lg-9 bs-component">

        @if($line->editInEditor())
            <textarea name="trans[{{ $locale }}][{{ $line->id }}]" id="{{ $locale }}-{{ $line->id }}-inputValue" class="input inset-s" data-editor rows="5">{!! old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)) !!}</textarea>
        @elseif($line->editInTextarea())
            <textarea name="trans[{{ $locale }}][{{ $line->id }}]" id="{{ $locale }}-{{ $line->id }}-inputValue" class="input inset-s" rows="5">{!! old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)) !!}</textarea>
        @else
            <input type="text" name="trans[{{ $locale }}][{{ $line->id }}]" id="{{ $locale }}-{{ $line->id }}-inputValue" class="input inset-s" value="{!! old('trans['.$locale.']['.$line->id.']',$line->getValue($locale,false)) !!}"/>
        @endif

        @if($line->description)
            <p class="subtle">{{ $line->description }}</p>
        @endif
    </div>
</div>
