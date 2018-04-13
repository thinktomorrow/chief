@extends('back._layouts.master')

@section('page-title', 'Article Management')


@section('content')
    WORK IN PROGRESS
    <div class="btn-group relative">
      <button type="button" class="btn btn-primary squished">Save</button>
      <button type="button" class="btn btn-primary squished dropdown-toggle" data-toggle="dropdown">
        <span class="icon icon-chevron-down"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#">As draft</a></li>
        <li><a href="#">In review</a></li>
      </ul>
    </div>

{{-- Artikel --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Paginainhoud</h2>
        <p class="caption">Dit is de artikelnaam zoals ze ook wordt weergegeven voor uw bezoekers.</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Titel</label>
        <input id="seo-title" class="input inset-s" placeholder="Titel" type="text" required="">
        <span class="stack text-default"><b>Permalink:</b> https://crius-group.com/<b>artikelnaam</b><button>edit</button></span>

        <div class="stack">
            <label for="seo-description">Inhoud</label>
            <textarea id="seo-description" cols="30" rows="20" class="input redactor inset-s" placeholder="Beschrijving" type="text" required=""></textarea>
        </div>

    </div>
</section>
<hr>
{{-- Modules --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Modules</h2>
        <p class="caption">Kies hier de modules die jij wil koppelen</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Modules</label>

        <div class="stack">
            <label for="seo-description">Inhoud</label>
            <textarea id="seo-description" cols="30" rows="20" class="input redactor inset-s" placeholder="Beschrijving" type="text" required=""></textarea>
        </div>

    </div>
</section>
<hr>
{{-- SEO --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Zoekmachines</h2>
        <p class="caption">Titel en omschrijving van het artikel zoals het in search engines (o.a. google) wordt weergegeven.</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Titel</label>
        <input id="seo-title" class="input inset-s" placeholder="Seo titel" type="text" required="">
        <div class="stack">
            <label for="seo-description">Beschrijving</label>
            <textarea id="seo-description" cols="30" rows="5" class="input inset-s" placeholder="Seo beschrijving" type="text" required=""></textarea>
        </div>

        <label for="seo-title"><i>Preview</i></label>
        <div class="panel seo-preview --border inset bc-success">
            <h2 class="text-information">SEO Titel</h2>
            <span class="link text-success">https://crius-group.com/article</span>
            <p class="caption">preview van description tekst hier</p>
        </div>
    </div>
</section>
<hr>
{{-- Zichtbaarheid --}}
<section class="row formgroup stack gutter-l">
    <div class="column-4">
        <h2 class="formgroup-label">Publicatie</h2>
        <p class="caption">Lorem ipsum</p>
    </div>
    <div class="formgroup-input column-7">
        <div class="custom-indicators">
            <label for="switch-1">Zichtbaar</label>
            <input class="switch switch-primary" id="switch-1" type="checkbox" checked/>
            <label class="custom-switch switch-btn" for="switch-1"></label>
        </div>

        <div class="stack">
            <label for="publication-date">Zichtbaar vanaf</label>
             <input type="datetime-local" name="publication-date" class="squished">
        </div>
    </div>
</section>
<hr>


@stop