@extends('chief::back._layouts.master')

@section('page-title', 'settings')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Settings')
    <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
@endcomponent

@section('content')
    <form action="{{ route('chief.back.settings.store') }}" id="updateForm" method="POST" role="form">
        {{ csrf_field() }}
        @foreach($settings as $setting)
            <section class="row formgroup gutter-xs">
                <div class="column-5">
                    <h2 class="formgroup-label">{{ $setting->field['label'] }}</h2>
                    <p class="caption">{{ $setting->field['description'] }}</p>
                </div>
                <div class="column-7">
                    @if($setting->field['type'] == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::SELECT)
                        <chief-multiselect
                                name="settings[{{ $setting->key }}]"
                                :options='@json($setting->field['options'])'
                                selected='@json(old($setting->key, $setting->field['selected']))'
                        >
                        </chief-multiselect>
                    @elseif($setting->field['type'] == \Thinktomorrow\Chief\Common\TranslatableFields\FieldType::TEXT)
                        <textarea class="inset-s" name="settings[{{$setting->key}}]" id="description" cols="10" rows="5">{{ $setting->value }}</textarea>
                    @else
                        <input type="text" name="settings[{{ $setting->key }}]" id="settings-{{ $setting->key }}" class="input inset-s" placeholder="{{ $placeholder ?? '' }}" value="{{ old('settings.' . $setting->key, $setting->value) }}">
                    @endif

                    <error class="caption text-warning" field="settings.{{ $setting->keyy }}" :errors="errors.get('settings')"></error>
                </div>
            </section>
        @endforeach
    </form>
@stop
