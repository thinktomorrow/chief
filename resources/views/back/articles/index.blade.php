@extends('back._layouts.master')

@section('page-title')
    Artikels
@stop

@component('back._layouts._partials.header')
    @slot('title', 'artikels')
    <a href="{{ route('back.articles.create') }}" class="btn btn-primary">
      <i class="icon icon-plus"></i>
      Voeg een artikel toe
    </a>
@endcomponent

@section('content')

    <div class="panel">
        <table class="table admin-form">
            <thead>
            <tr class="bg-light">
                <th style="width:5%"></th>
                <th style="width:15%">Titel</th>
                <th style="width:35%">Fragment</th>
                <th style="width:10%">Aangepast</th>
                <th style="width:20%">Online</th>
                <th style="width:10%"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($articles as $article)
                <tr>
                    <td style="width:5%">
                        @if ($article->hasFile('banner'))
                            <img class="img-responsive mw300 rounded" src="{!! $article->getFileUrl('banner', 'thumb') !!}" alt="Thumb">
                        @endif
                    </td>
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
                            <div class="btn-group">
                                <div class="publishMedia">
                                    <div class="publishActions-{{$article->id}} hidden">
                                        <span class="btn btn-warning disabled">{{ $article->isPublished() ? 'Draft' : 'Publish' }} article ?</span>
                                        <a class="btn btn-primary noPublish" data-publish-id="{{$article->id}}"> <i class="fa fa-times"></i> </a>
                                        <input type="hidden" name="checkboxStatus" value="{{ $article->isPublished() }}">
                                        <input type="hidden" name="id" value="{{ $article->id }}">
                                        <button type="submit" class="btn btn-default mr5"> <i class="fa fa-check"></i>  </button>
                                    </div>
                                    <div class="btn btn-{{ $article->isPublished() ? 'success' : 'info' }} mr5 showPublishOptions-{{$article->id}}" data-publish-id="{{$article->id}}">
                                        {{ $article->isPublished() ? 'Online' : 'Offline' }}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </td>

                    <td style="width:10%;" class="text-right">
                        <a title="View {{ $article->title }} on site" href="{{ route('back.articles.show',$article->slug) }}?preview-mode=true" target="_blank" class="btn btn-rounded btn-info btn-xs"><i class="fa fa-eye"></i></a>
                        <a title="Edit {{ $article->title }}" href="{{ route('back.articles.edit',$article->getKey()) }}" class="btn btn-rounded btn-success btn-xs"><i class="fa fa-edit"></i> </a>
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
		$('.publishActions-'+id).removeClass('hidden');
		$('.showPublishOptions-'+id).addClass('hidden');
	});
	$('.noPublish').click(function(){
		var id = this.dataset.publishId;
		$('.publishActions-'+id).addClass('hidden');
		$('.showPublishOptions-'+id).removeClass('hidden');
	});
</script>
@endpush