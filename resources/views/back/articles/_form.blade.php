{{-- note: locale must be passed to this form which makes each formtab unique --}}
<article class="panel panel-dark">
  <div class="panel-heading">Artikel</div>
  <div class="panel-body br-n p15">
    <div class="bs-component">
      <label for="{{$locale}}-inputTitle">Title</label>
      <div class="bs-component">
        {!! Form::text('trans['.$locale.'][title]',null,['id' => $locale.'-inputTitle','class' =>'form-control']) !!}
      </div>
    </div>

    <div class="bs-component">
      <label for="{{ $locale }}-inputDescription">Inhoud</label>
      <div class="bs-component">
        {!! Form::textarea('trans['.$locale.'][content]',null,['id' => $locale.'-inputDescription','class' => 'form-control redactor-editor']) !!}
      </div>
    </div>
  </div>
</article>

<article class="panel panel-dark">
  <div class="panel-heading">Samenvatting</div>
  <div class="panel-body br-n p15">
    <label for="{{ $locale }}-inputTeaser">Inhoud
      <span data-toggle="tooltip" title="Optioneel met een maximum van 500 tekens. Deze tekst wordt getoond op het nieuwsoverzicht."><i class="fa fa-question-circle"></i></span>
    </label>

    <div class="bs-component">
      {!! Form::textarea('trans['.$locale.'][short]',null,['id' => $locale.'-inputTeaser','class' => 'form-control redactor-editor','rows' => 4]) !!}
    </div>
  </div>
</article>

<article class="panel panel-dark">
  <div class="panel-heading">SEO omschrijving</div>
  <div class="panel-body br-n p15">
    <label for="{{ $locale }}-inputMetaDescription">Inhoud</label>
    <div class="bs-component">
      {!! Form::textarea('trans['.$locale.'][meta_description]',null,['id' => $locale.'-inputMetaDescription','class' => 'form-control','rows' => '3']) !!}
    </div>
  </div>
</article>

<!-- IMAGE UPLOADS -->
<article class="panel panel-dark pn col-md-6">
  <div class="panel-heading">Afbeeldingen</div>
  <div class="panel-body br-n p15">
    @if($article->hasFile('banner', $locale))
      <div class="well wel-sm">
          <span class="overflow-ellipsis">{{ $article->getFileName('banner', $locale) }}</span>
          <a href="{{ $article->getFileUrl('banner', '', $locale) }}" class="preview">
            <span class="pull-right">Preview <i class="fa fa-eye mr5"></i></span>
          </a>
      </div>
    @endif
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
<article class="panel panel-dark pn pl10 col-md-6">
  <div class="panel-heading">Bijlages</div>
  <div class="panel-body br-n p15">
      @if($article->hasFile('pdf', $locale))
        <div class="well wel-sm">
        <span class="overflow-ellipsis">{{ $article->getFileName('pdf', $locale) }}</span>
          <a href="{{ $article->getFileUrl('pdf', '', $locale) }}" target="_blank">
            <span class="pull-right">Open pdf <i class="fa fa-eye mr5"></i></span>
          </a>
        </div>
      @endif
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