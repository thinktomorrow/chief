@extends('chief::back._layouts.master')

@section('page-title','Voeg nieuw menu-item toe')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Menu item bewerken.')
        <button data-submit-form="createForm" type="button" class="btn btn-primary">Opslaan</button>
    @endcomponent

    @section('content')

        <form id="createForm" method="POST" action="{{ route('chief.back.menu.update', $menu->id) }}" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            <div class="row stack gutter-l inset selected">
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
                    <label>Menu label</label>
                    <input type="text" name="collection-label" id="collection-label" placeholder="Menu label" value="" class="input inset-s">
                    <div class="stack">
                        <label>Een collectie van:</label>
                        <chief-multiselect name="category" :options="['first','second','third']"></chief-multiselect>
                    </div>
                </div>
            </div>
            <div class="row stack gutter-l inset">
                <div class="column-4">
                    <label for="check-2" class="custom-indicators">
                        <input value="radio" name="radio" id="check-2" type="radio">
                        <span class="custom-radiobutton inline"></span>
                        <div class="label-content">
                            <h2 class="formgroup-label">Interne link toevoegen</h2>
                            <p class="caption">Een interne link is een verwijzing naar een bestaande pagina op de website.</p>
                        </div>
                    </label>
                </div>
                <div class="column-8 disabled">
                    <label>Menu label</label>
                    <input type="text" name="intern-label" id="intern-label" placeholder="Menu label" value="" class="input inset-s">
                    <div class="stack">
                        <label>Interne link naar</label>
                        <chief-multiselect name="category" :options="['first','second','third']"></chief-multiselect>
                    </div>
                </div>
            </div>
            <div class="row stack gutter-l inset">
                <div class="column-4">
                    <label for="check-3" class="custom-indicators">
                        <input value="radio" name="radio" id="check-3" type="radio">
                        <span class="custom-radiobutton inline"></span>
                        <div class="label-content">
                            <h2 class="formgroup-label">Custom link toevoegen</h2>
                            <p class="caption">Een custom link kan verwijzen naar een externe pagina of naar een zelf opgegeven url.</p>
                        </div>
                    </label>
                </div>
                <div class="column-8 disabled">
                    <label>Menu label</label>
                    <input type="text" name="custom-label" id="custom-label" placeholder="Menu label" value="" class="input inset-s">
                    <div class="stack">
                        <label>Custom link naar</label>
                        <input type="text" name="url-label" id="collection-label" placeholder="Voer een URL in" value="" class="input inset-s">
                    </div>
                </div>
            </div>

        </form>

    @stop
