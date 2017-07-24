{{-- note: locale must be passed to this form which makes each formtab unique --}}
<article class="panel">
  <div class="panel-heading">Artikel</div>
  <div class="panel-body br-n pt15 pn">
    <div class="form-group">
      <label class="col-lg-12" for="{{$locale}}-inputTitle">Title</label>
      <div class="col-lg-12 bs-component">
        {!! Form::text('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control']) !!}
      </div>
    </div>

    <div class="form-group">
      <label class="col-lg-12" for="{{ $locale }}-inputDescription">Inhoud
        @if(!$article->id)
          <span class="subtle pull-right" data-toggle="tooltip" title="Afbeeldingen kunnen pas worden toegevoegd nadat een artikel is aangemaakt"><i class="fa fa-question-circle"></i> geen afbeelding?</span>
        @endif
      </label>

      <div class="col-lg-12 bs-component">
        {!! Form::textarea('trans['.$locale.'][content]',null,['id' => $locale.'-inputDescription','class' => 'form-control redactor-editor']) !!}
      </div>
    </div>
  </div>
</article>

<article class="panel">
  <div class="panel-heading">Samenvatting</div>
  <div class="panel-body br-n pt15 pn form-group">
    <label class="col-lg-12" for="{{ $locale }}-inputTeaser">Inhoud
      <span data-toggle="tooltip" title="Optioneel met een maximum van 500 tekens. Deze tekst wordt getoond op het nieuwsoverzicht."><i class="fa fa-question-circle"></i></span>
    </label>

    <div class="col-lg-12 bs-component">
      {!! Form::textarea('trans['.$locale.'][short]',null,['id' => $locale.'-inputTeaser','class' => 'form-control redactor-editor','rows' => 4]) !!}
    </div>
  </div>
</article>

<article class="panel">
  <div class="panel-heading">SEO omschrijving</div>
  <div class="panel-body br-n pt15 pn form-group">
    <label class="col-lg-12" for="{{ $locale }}-inputMetaDescription">Inhoud</label>
    <div class="col-lg-12 bs-component">
      {!! Form::textarea('trans['.$locale.'][meta_description]',null,['id' => $locale.'-inputMetaDescription','class' => 'form-control','rows' => '3']) !!}
    </div>
  </div>
</article>

<!-- IMAGE UPLOADS -->
<article class="panel pn col-md-6">
  <div class="panel-heading">Afbeeldingen</div>
  <div class="panel-body">
    <div class="well wel-sm">
      @if($article->hasFile('banner', $locale))
        <span class="overflow-ellipsis">{{ $article->getFileName('banner', $locale) }}</span>
        <a href="{{ $article->getFileUrl('banner', '', $locale) }}" class="preview">
          <span class="pull-right">Preview <i class="fa fa-eye mr5"></i></span>
        </a>
      @endif
    </div>
    <button type="button" class="btn btn-default mr5" id="showBannerUploadPanel-{{ $locale }}" data-upload-locale="{{ $locale }}">
      <span class="fa fa-image"></span>
      Upload nieuwe banner
    </button>
  </div>
  @push('sidebar')
    @include('back.articles._bannerupload')
  @endpush
</article>
<!-- PDF UPLOADS -->
<article class="panel pn pl10 col-md-6">
  <div class="panel-heading">Bijlages</div>
  <div class="panel-body">
    <div class="well wel-sm">
      @if($article->hasFile('pdf', $locale))
        <span class="overflow-ellipsis">{{ $article->getFileName('pdf', $locale) }}</span>
        <a href="{{ $article->getFileUrl('pdf', '', $locale) }}" target="_blank">
          <span class="pull-right">Open pdf <i class="fa fa-eye mr5"></i></span>
        </a>
      @endif
    </div>
    <button type="button" class="btn btn-default mr5" id="showPdfUploadPanel-{{ $locale }}" data-upload-locale="{{ $locale }}">
      <span class="fa fa-file-pdf-o"></span>
      Upload nieuwe pdf
    </button>
    @push('sidebar')
      @include('back.articles._pdfupload')
    @endpush
  </div>
</article>
@push('custom-scripts')
<!-- SHOW IMAGE FROM UPLOAD IN A POPUP   -->
<script>
  $(document).ready(function(){
    $('.preview').magnificPopup({
        type: 'image',
    });
  });
</script>
@endpush