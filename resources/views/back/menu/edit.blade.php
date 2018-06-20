@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item bewerken.')
        <button data-submit-form="updateForm" type="button" class="btn btn-primary">Opslaan</button>
    @endcomponent

    @section('content')

        <form id="updateForm" method="POST" action="{{ route('chief.back.menu.update', $menu->id) }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <tabs v-cloak>
                    @foreach($menu->availableLocales() as $locale)
                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                            <div class="column-4">
                                <label>Menu label</label>
                            </div>
                            <div class="column-8">
                                <input type="text" name="trans[{{ $locale }}][label]" id="trans-{{ $locale }}-label" placeholder="Menu label" value="{{ old('trans.'.$locale.'.label', $menu->label) }}" class="input inset-s">                    
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.label" :errors="errors.get('trans.{{ $locale }}')"></error>
                        </tab>
                    @endforeach
                </tabs>
            @if($menu->type == 'collection')
                <div class="row stack gutter-l inset">
                    <div class="column-4">
                        <label for="check-1" class="custom-indicators">
                            <input value="radio" name="radio" id="check-1" type="radio" checked="checked">
                            <span class="custom-radiobutton inline"></span>
                            <div class="label-content">
                                <h2 class="formgroup-label text-primary">Collectie toevoegen</h2>
                                <p class="caption">Een collectie haalt alle sub-items op van de gekozen collectie en voegt deze toe aan het menu.</p>
                            </div>
                        </label>
                    </div>
                    <div class="column-8">
                        <div class="stack">
                            <label>Een collectie van:</label>
                            <chief-multiselect name="category" :options="['first','second','third']"></chief-multiselect>
                        </div>
                    </div>
                </div>
            @elseif($menu->type == 'internal')
                <div class="row stack gutter-l inset ">
                    <div class="column-4">
                        <label for="check-2" class="custom-indicators">
                            <input value="radio" name="radio" id="check-2" type="radio" checked="checked">
                            <span class="custom-radiobutton inline"></span>
                            <div class="label-content">
                                <h2 class="formgroup-label">Interne link toevoegen</h2>
                                <p class="caption">Een interne link is een verwijzing naar een bestaande pagina op de website.</p>
                            </div>
                        </label>
                    </div>
                    <div class="column-8 ">
                        <div class="stack">
                            <label>Interne link naar</label>
                            <chief-multiselect name="category" :options="['first','second','third']"></chief-multiselect>
                        </div>
                    </div>
                </div>
            @elseif($menu->type == 'custom')
                <div class="row stack gutter-l inset ">
                    <div class="column-4">
                        <label for="check-3" class="custom-indicators">
                            <input value="radio" name="radio" id="check-3" type="radio" checked="checked">
                            <span class="custom-radiobutton inline"></span>
                            <div class="label-content">
                                <h2 class="formgroup-label">Custom link toevoegen</h2>
                                <p class="caption">Een custom link kan verwijzen naar een externe pagina of naar een zelf opgegeven url.</p>
                            </div>
                        </label>
                    </div>
                    <div class="column-8 ">
                        <div class="stack">
                            <label>Custom link naar</label>
                            <input type="text" name="url" id="url" placeholder="Voer een URL in" value="{{ $menu->url }}" class="input inset-s">
                        </div>
                    </div>
                </div>
            @endif
        </form>

    @stop
