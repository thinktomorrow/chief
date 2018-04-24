@extends('back._layouts.master')

@section('page-title', 'Artikels')

@component('back._layouts._partials.header')
    @slot('title', 'Jouw artikels')
    <a href="{{ route('back.articles.create') }}" class="btn btn-primary">
      <i class="icon icon-plus"></i>
      Voeg een artikel toe
    </a>
@endcomponent

@section('content')
    <tabs>
        <tab name="Published (12)">
            @foreach($articles as $article)
                <div class="row center-center stretched">
                    <div class="column-8">
                        <div class="column-12">
                            <a href="{{ route('back.articles.edit',$article->getKey()) }}" class="left">
                                <h2>{{ $article->getTranslationFor('title') }}</h2>
                            </a>
                            <a title="View {{ $article->title }} on site" href="{{ route('back.articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="btn btn-link text-secondary">preview <i class="icon icon-fw icon-eye"></i></a>
                        </div>
                        <div class="column-12">
                            {{ teaser($article->content,150,'...') }}
                        </div>
                        <span class="text-subtle">Laatst aangepast op: {{ $article->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="btn-group column">
                        <form action="{{ route('back.articles.publish') }}" method="POST">
                            {{ csrf_field() }}
                                <div class="publishMedia ">
                                    <div class="publishActions-{{$article->id}} hidden">
                                        <input type="hidden" name="checkboxStatus" value="{{ $article->isPublished() }}">
                                        <input type="hidden" name="id" value="{{ $article->id }}">
                                        <button type="submit" class="btn btn-o-secondary">Plaats {{ $article->isPublished() ? 'in draft' : 'online' }}  <i class="icon icon-check"></i>  </button>
                                    </div>
                                </div>
                        </form>

                        <a href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn btn-link text-secondary">Aanpassen</a>
                    </div>
                </div>
                <hr>
            @endforeach

        </tab>
        <tab name="Drafts (3)">
            <div class="row stretched">
                <div class="column-12">
                    <a href="#">
                        <h2>Werkdocument</h2>
                    </a>
                </div>
                <div class="column-6">
                    {{ teaser($article->content,150,'...') }}
                </div><br>
                <span class="text-subtle">Laatst aangepast op: {{ $article->updated_at->format('d/m/Y') }}</span>

            </div>
            <hr>
        </tab>
        <tab name="Archief (1)"></tab>
    </tabs>

    <div class="panel">
        <table class="table admin-form">
            <tbody>
            @foreach($articles as $article)
                <tr>
                    <td style="width:15%">
                        <a href="{{ route('back.articles.edit',$article->getKey()) }}">
                            @foreach($article->getUsedLocales() as $usedLocale)
                                {{ $article->getTranslationFor('title',$usedLocale) }}
                            @endforeach
                        </a>
                    </td>
                    <td class="subtle" style="width:35%">
                        {{ teaser($article->content,150,'...') }}
                    </td>
                    <td class="subtle" style="width:10%">
                        {{ $article->updated_at->format('d/m/Y H:i') }}
                    </td>
                    <td style="width:20%">
                        {{--<div class="switch switch-success round switch-inline">--}}
                            {{--{!! Form::checkbox('published',1,$article->isPublished(),['data-publish-toggle'=>$article->id,'id' => "switch{$article->id}"]) !!}--}}
                            {{--<label title="{{ $article->isPublished()?'Online':'Offline' }}" for="switch{{$article->id}}"></label>--}}
                        {{--</div>--}}
                        <form action="{{ route('back.articles.publish') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="">
                                <div class="publishMedia">
                                    <div class="publishActions-{{$article->id}} hidden">
                                        {{-- <span class="btn btn-warning disabled">{{ $article->isPublished() ? 'Draft' : 'Publish' }} article ?</span> --}}
                                        <a class="btn btn-primary noPublish" data-publish-id="{{$article->id}}"> <i class="icon icon-clock"></i> </a>
                                        <input type="hidden" name="checkboxStatus" value="{{ $article->isPublished() }}">
                                        <input type="hidden" name="id" value="{{ $article->id }}">
                                        <button type="submit" class="btn btn-default mr5"> <i class="icon icon-check"></i>  </button>
                                    </div>
                                    <div class="btn btn-{{ $article->isPublished() ? 'success' : 'info' }} mr5 showPublishOptions-{{$article->id}}" data-publish-id="{{$article->id}}">
                                        {{ $article->isPublished() ? 'Online' : 'Offline' }}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </td>

                    <td style="width:10%;" class="text-right">
                        <a title="View {{ $article->title }} on site" href="{{ route('back.articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="btn btn-link btn-info btn-xs"><i class="icon icon-eye"></i></a>
                        <a title="Edit {{ $article->title }}" href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn btn-link btn-xs"><i class="icon icon-edit"></i> </a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-center">
        {!!  $articles->render() !!}
    </div>
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