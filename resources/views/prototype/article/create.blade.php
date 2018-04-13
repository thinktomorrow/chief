@extends('back._layouts.master')

@section('page-title', 'Article Management')


@section('content')
{{-- SEO --}}
<section class="row formgroup stack gutter-l">
    <div class="column-5">
        <h2 class="formgroup-label">SEO OMSCHRIJVING</h2>
        <p class="caption">Titel en omschrijving van het artikel zoals het in search engines (o.a. google) wordt weergegeven.</p>
    </div>
    <div class="formgroup-input column-7">
        <label for="seo-title">Titel</label>
        <input id="seo-title" class="input inset-s" placeholder="Seo titel" type="text" required="">
        <div class="stack">
            <label for="seo-description">Beschrijving</label>
            <textarea id="seo-description" cols="30" rows="10" class="input inset-s" placeholder="Seo beschrijving" type="text" required=""></textarea>
        </div>

        <label for="seo-title"><i>Preview</i></label>
        <div class="panel seo-preview --border inset bc-success">
            <h2 class="text-information">Titel</h2>
            <span class="link text-success">https://crius-group.com/article</span>
            <p class="caption">Uw klant verwacht uw boek ook online te kunnen vinden. Wij hebben verstand van digitaal uitgeven en tonen respect voor uw papieren product. Lees meer… Educatief. Breng uw content onder de aandacht van uw bladerende en swipende klant. Wij helpen u bij het inrichten van uw uitgeefproces.</p>
        </div>
    </div>
</section>

<hr>


HERE IT COMES

@stop