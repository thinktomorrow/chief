@extends('chief::back._layouts.master')

@section('page-title', 'settings')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Settings')
    <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
@endcomponent

@section('content')
    <form action="{{ route('chief.back.settings.update') }}" id="updateForm" method="POST" role="form">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        @foreach($settings as $setting)
            <section class="row formgroup gutter-xs">
                <div class="column-5">
                    <h2>{{ $setting->field->label }}</h2>
                    <p class="caption">{{ $setting->field->description }}</p>
                </div>
                <div class="column-7">
                    @if($setting->field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::SELECT)
                        <chief-multiselect
                                name="settings[{{ $setting->key }}]"
                                :options='@json($setting->field->options)'
                                selected='@json(old($setting->key, $setting->field->selected))'
                        >
                        </chief-multiselect>
                    @elseif($setting->field->type == \Thinktomorrow\Chief\Fields\Types\Types\FieldType::TEXT)
                        <textarea class="inset-s" name="settings[{{$setting->key}}]" id="description" cols="10" rows="5">{{ $setting->value }}</textarea>
                    @else
                        <input type="text" name="settings[{{ $setting->key }}]" id="settings-{{ $setting->key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('settings.' . $setting->key, $setting->value) }}">
                    @endif

                    <error class="caption text-warning" field="settings.{{ $setting->key }}" :errors="errors.get('settings')"></error>
                </div>
            </section>
        @endforeach
    </form>
@stop
