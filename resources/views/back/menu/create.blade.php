@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item toevoegen.')
        <button data-submit-form="createForm" type="button" class="btn btn-primary">Toevoegen</button>
    @endcomponent

    @section('content')

        <form id="createForm" method="POST" action="{{ route('chief.back.menu.store') }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}

            <div class="row stack gutter-l inset">
                <tabs v-cloak>
                    @foreach($menuitem->availableLocales() as $locale)
                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                            <div class="column-4">
                                <label>Menu label</label>
                            </div>
                            <div class="column-8">
                                <input type="text" name="trans[{{ $locale }}][label]" id="trans-{{ $locale }}-label" placeholder="Menu label" value="{{ old('trans.'.$locale.'.label') }}" class="input inset-s">
                            </div>

                            <error class="caption text-warning" field="trans.{{ $locale }}.label" :errors="errors.get('trans.{{ $locale }}')"></error>
                        </tab>
                    @endforeach
                </tabs>
                <div class="column-4">
                    <label for="check-2" class="custom-indicators">
                        <input value="internal" name="type" id="check-2" type="radio" checked="checked">
                        <span class="custom-radiobutton inline"></span>
                        <div class="label-content">
                            <h2 class="formgroup-label">Interne link toevoegen</h2>
                            <p class="caption">Een interne link is een verwijzing naar een bestaande pagina op de website.</p>
                        </div>
                    </label>
                </div>
                <div class="column-8">
                    <div class="stack">
                        <label>Interne link naar</label>
                        <chief-multiselect
                                name="page_id"
                                :options='@json($pages)'
                                grouplabel="group"
                                groupvalues="values"
                                labelkey="label"
                                valuekey="id"
                        >
                        </chief-multiselect>
                    </div>
                </div>
            </div>
            <div class="row stack gutter-l inset">
                <div class="column-4">
                    <label for="check-3" class="custom-indicators">
                        <input value="custom" name="type" id="check-3" type="radio">
                        <span class="custom-radiobutton inline"></span>

                        <div class="label-content">
                            <h2 class="formgroup-label">Custom link toevoegen</h2>
                            <p class="caption">Een custom link kan verwijzen naar een externe pagina of naar een zelf opgegeven url.</p>
                        </div>
                    </label>
                </div>
                <div class="column-8">
                    <div class="stack">
                        <tabs v-cloak>
                            @foreach($menuitem->availableLocales() as $locale)
                            <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.label')}">
                                <div class="column-4">
                                    <label>Custom link naar</label>
                                </div>
                                <div class="column-8">
                                    <input type="text" name="trans[{{ $locale }}][url]" id="trans-{{ $locale }}-url" placeholder="Voer een URL in" value="{{ old('trans.'.$locale.'.url') }}"
                                        class="input inset-s">
                                </div>

                                <error class="caption text-warning" field="trans.{{ $locale }}.label" :errors="errors.get('trans.{{ $locale }}')"></error>
                            </tab>
                            @endforeach
                        </tabs>
                    </div>
                </div>
            </div>
            <div class="row stack gutter-l inset disabled">
                    <div class="column-4">
                        <label for="check-1" class="custom-indicators">
                            <input value="collection" name="type" id="check-1" type="radio">
                            <span class="custom-radiobutton inline"></span>
                            <div class="label-content">
                                <h2 class="formgroup-label">Collectie toevoegen</h2>
                                <p class="caption">Een collectie haalt alle sub-items op van de gekozen collectie en voegt deze toe aan het menu.</p>
                            </div>
                        </label>
                    </div>
                    <div class="column-8">
                        <div class="stack">
                            <label>Een collectie van:</label>
                            {{-- <chief-multiselect name="category" :options="['first','second','third']"></chief-multiselect> --}}
                        </div>
                    </div>
                </div>
        </form>

    @stop
