{{-- note: locale must be passed to this form which makes each formtab unique --}}
<div class="form-group">
    <label class="col-lg-12" for="{{$locale}}-inputTitle">Title</label>
    <div class="col-lg-12 bs-component">
        {!! Form::text('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-12" for="{{ $locale }}-inputDescription">Artikel
        @if(!$article->id)
            <span class="subtle pull-right" data-toggle="tooltip" title="Afbeeldingen kunnen pas worden toegevoegd na creatie van het artikel."><i class="fa fa-question-circle"></i> geen afbeelding?</span>
        @endif
    </label>

    <div class="col-lg-12 bs-component">
        {!! Form::textarea('trans['.$locale.'][content]',null,['id' => $locale.'-inputDescription','class' => 'form-control redactor-editor']) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-12" for="{{ $locale }}-inputTeaser">Introductie
        <span data-toggle="tooltip" title="Optioneel met een maximum van 500 tekens. Deze tekst wordt getoond op het nieuwsoverzicht."><i class="fa fa-question-circle"></i></span>
    </label>

    <div class="col-lg-12 bs-component">
        {!! Form::textarea('trans['.$locale.'][short]',null,['id' => $locale.'-inputTeaser','class' => 'form-control redactor-editor','rows' => 4]) !!}
    </div>
</div>

<div class="form-group">
    <label class="col-lg-12" for="{{ $locale }}-inputMetaDescription">SEO omschrijving</label>
    <div class="col-lg-12 bs-component">
        {!! Form::textarea('trans['.$locale.'][meta_description]',null,['id' => $locale.'-inputMetaDescription','class' => 'form-control','rows' => '3']) !!}
    </div>
</div>

<div class="form-group">
    <div>
        @if($article->hasFile('pdf-'.$locale))
            <a href="{{ $article->getFileUrl('pdf-'.$locale) }}" target="_blank">{{ $article->getFileName('pdf-'.$locale) }}</a>
        @endif
    </div>
    <button type="button" class="btn btn-default mr5" id="showPdfUploadPanel-{{ $locale }}" data-upload-locale="{{ $locale }}">
        <span class="fa fa-upload"></span>
        Upload nieuw pdf
    </button>
</div>
@push('sidebar')
    @include('back.articles._pdfupload')
@endpush

<div class="form-group">
    <div>
        @if($article->hasFile('banner-'.$locale))
            <a href="{{ $article->getFileUrl('banner-'.$locale) }}" target="_blank">{{ $article->getFileName('banner-'.$locale) }}</a>
        @endif
    </div>
    <button type="button" class="btn btn-default mr5" id="showBannerUploadPanel-{{ $locale }}" data-upload-locale="{{ $locale }}">
        <span class="fa fa-upload"></span>
        Upload nieuw banner
    </button>
</div>
@push('sidebar')
    @include('back.articles._bannerupload')
@endpush
