@extends('back._layouts.master')

@section('page-title', 'Artikels')

@component('back._layouts._partials.header')
    @slot('title', 'Jouw artikels')
    <button @click="showModal('delete-article')" class="btn btn-o-tertiary">
      <i class="icon icon-trash"></i>
      Verwijder artikel
  </button>
    <a href="{{ route('back.articles.create') }}" class="btn btn-primary">
      <i class="icon icon-plus"></i>
      Voeg een artikel toe
    </a>
@endcomponent

@section('content')
    <tabs>
        <tab name="Drafts (3)">
            @foreach($drafts as $article)
                <div class="row center-center">
                    <div>
                        <label for="check-{{ $article->id }}" class="column-12 custom-indicators">
                            <input value="checkbox" id="check-{{ $article->id }}" type="checkbox"> <span class="custom-checkbox"></span>
                        </label>
                    </div>
                    <div class="column-8 stretched">
                        <div class="column-12">
                                <h2>
                                    {{ $article->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $article->title }}" href="{{ route('back.articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                        </div>
                        <div class="column-12">
                            {{ teaser($article->content,150,'...') }}
                        </div>
                        <span class="text-subtle">Laatst aangepast op: {{ $article->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="center-y column">
                        <div class="dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-secondary">
                                <i class="icon icon-chevron-down"></i>
                                <div class="dropdown-menu">
                                    <div><a href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn text-secondary">Aanpassen</a></div>
                                    <div><a @click="showModal('delete-article')">Archiveer artikel</a></div>
                                    <div>
                                        <form action="{{ route('back.articles.publish') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="publishMedia ">
                                                <div class="publishActions-{{$article->id}} hidden">
                                                    <input type="hidden" name="checkboxStatus" value="{{ $article->isPublished() }}">
                                                    <input type="hidden" name="id" value="{{ $article->id }}">
                                                    <button type="submit" class="btn btn-icon btn-{{ $article->isPublished() ? 'secondary' : 'primary' }}">{{ $article->isPublished() ? 'online' : 'draft' }} <i class="icon icon-{{ $article->isPublished() ? 'clock' : 'check' }}"></i>  </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        </tab>
        <tab name="Ingepland (1)"></tab>
        <tab name="Published (12)">
            @foreach($published as $article)
                <div class="row center-center">
                    <div>
                        <label for="check-{{ $article->id }}" class="column-12 custom-indicators">
                            <input value="checkbox" id="check-{{ $article->id }}" type="checkbox"> <span class="custom-checkbox"></span>
                        </label>
                    </div>
                    <div class="column-8 stretched">
                        <div class="column-12">
                                <h2>
                                    {{ $article->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $article->title }}" href="{{ route('back.articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                        </div>
                        <div class="column-12">
                            {{ teaser($article->content,150,'...') }}
                        </div>
                        <span class="text-subtle">Laatst aangepast op: {{ $article->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="center-y column">
                        <div class="dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-secondary">
                                <i class="icon icon-chevron-down"></i>
                                <div class="dropdown-menu">
                                    <div><a href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn text-secondary">Aanpassen</a></div>
                                    <div><a @click="showModal('delete-article')">Archiveer artikel</a></div>
                                    <div>
                                        <form action="{{ route('back.articles.publish') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="publishMedia ">
                                                <div class="publishActions-{{$article->id}} hidden">
                                                    <input type="hidden" name="checkboxStatus" value="{{ $article->isPublished() }}">
                                                    <input type="hidden" name="id" value="{{ $article->id }}">
                                                    <button type="submit" class="btn btn-icon btn-{{ $article->isPublished() ? 'secondary' : 'primary' }}">{{ $article->isPublished() ? 'online' : 'draft' }} <i class="icon icon-{{ $article->isPublished() ? 'clock' : 'check' }}"></i>  </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
            <div class="text-center">
                {!! $published->render() !!}
            </div>
        </tab>
        <tab name="Archief (1)"></tab>

    </tabs>


    @include('back.articles._partials.delete-modal')

@stop

@push('custom-scripts')
<script>
	// SHOW OR HIDE PUBLISH BUTTON
	$("[class^='showPublishOptions-'], [class*='showPublishOptions-']").click(function(){
		var id = this.dataset.publishId;
		$('.publishActions-'+id).removeClass('--hidden');
		$('.showPublishOptions-'+id).addClass('--hidden');
	});
	$('.noPublish').click(function(){
		var id = this.dataset.publishId;
		$('.publishActions-'+id).addClass('--hidden');
		$('.showPublishOptions-'+id).removeClass('--hidden');
	});
</script>
@endpush