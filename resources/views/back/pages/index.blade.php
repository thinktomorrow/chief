@extends('back._layouts.master')

@section('page-title', 'paginas')

@component('back._layouts._partials.header')
    @slot('title', 'Jouw paginas')
    <button @click="showModal('delete-page')" class="btn btn-o-tertiary">
      <i class="icon icon-trash"></i>
      Verwijder pagina
    </button>
    <a href="{{ route('back.pages.create') }}" class="btn btn-primary">
      <i class="icon icon-plus"></i>
      Voeg een pagina toe
    </a>
@endcomponent

@section('content')
    <tabs v-cloak>
        <tab name="Drafts ({{$drafts->count()}})">
            @foreach($drafts as $page)
                <div class="row center-center">
                    <div>
                        <label for="check-{{ $page->id }}" class="column-12 custom-indicators">
                            <input value="checkbox" id="check-{{ $page->id }}" type="checkbox">
                            <span class="custom-checkbox"></span>
                        </label>
                    </div>
                    <div class="column-8 stretched">
                        <div class="column-12">
                                <h2>
                                <a href="{{ route('back.pages.edit',$page->getKey()) }}">{{ $page->getTranslationFor('title') }}</a>
                                <a title="Bekijk {{ $page->title }}" href="{{ route('demo.pages.show', $page->slug) }}?preview-mode" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                        </div>
                        <div class="column-12">
                            {{ teaser($page->content,150,'...') }}
                        </div>
                        <div>
                            <span class="text-subtle">
                                <a href="{{ route('back.pages.edit',$page->getKey()) }}">Aanpassen</a>
                            </span>
                        </div>
                        <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="center-y column">
                        <div class="dropdown">
                            <form action="{{ route('back.pages.publish') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="publishMedia ">
                                    <div class="publishActions-{{$page->id}} hidden">
                                        <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                                        <input type="hidden" name="id" value="{{ $page->id }}">
                                        <button type="submit" class="btn btn-icon btn-{{ $page->isPublished() ? 'secondary' : 'primary' }}">{{ $page->isPublished() ? 'online' : 'draft' }} <i class="icon icon-{{ $page->isPublished() ? 'clock' : 'check' }}"></i>  </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr>
            @endforeach
        </tab>
        <tab name="Ingepland (1)">
            <p>Not implemented yet.</p>
        </tab>
        <tab name="Published ({{ $published->count() }})">
            @foreach($published as $page)
                <div class="row center-center">
                    <div>
                        <label for="check-{{ $page->id }}" class="column-12 custom-indicators">
                            <input value="checkbox" id="check-{{ $page->id }}" type="checkbox"> <span class="custom-checkbox"></span>
                        </label>
                    </div>
                    <div class="column-8 stretched">
                        <div class="column-12">
                                <h2>
                                    {{ $page->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $page->title }}" href="{{ route('back.pages.show',$page->slug) }}?preview-mode=true" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                        </div>
                        <div class="column-12">
                            {{ teaser($page->content,150,'...') }}
                        </div>
                        <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="center-y column">
                        <div class="dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-secondary">
                                <i class="icon icon-chevron-down"></i>
                                <div class="dropdown-menu">
                                    <div><a href="{{ route('back.pages.edit',$page->getKey()) }}" class="btn text-secondary">Aanpassen</a></div>
                                    <div><a @click="showModal('delete-page')">Archiveer pagina</a></div>
                                    <div>
                                        <form action="{{ route('back.pages.publish') }}" method="POST">
                                            {{ csrf_field() }}
                                            <div class="publishMedia ">
                                                <div class="publishActions-{{$page->id}} hidden">
                                                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                                                    <input type="hidden" name="id" value="{{ $page->id }}">
                                                    <button type="submit" class="btn btn-icon btn-{{ $page->isPublished() ? 'secondary' : 'primary' }}">{{ $page->isPublished() ? 'online' : 'draft' }} <i class="icon icon-{{ $page->isPublished() ? 'clock' : 'check' }}"></i>  </button>
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
        <tab name="Archief (1)">
            <p>Not implemented yet.</p>
        </tab>

    </tabs>


    @include('back.pages._partials.delete-modal')

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