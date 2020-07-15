@php
    $path = request()->path() == '/' ? '' : request()->path();
    $model = $model ?? $page;
@endphp
<link rel="canonical" href="{{ config('app.url') . '/' . $path }}" />
@if($locales)
    @foreach($locales as $locale)
        @php

            try{
                $localized_path = Thinktomorrow\Chief\Urls\MemoizedUrlRecord::findByModel($model, $locale)->slug;
            }catch(Thinktomorrow\Chief\Urls\UrlRecordNotFound $ex){
                continue;
            }

            $link = config('app.url') . '/' . $locale . ($localized_path == '/' ? '' :  '/'. $localized_path);
        @endphp
        @if(!$link) @continue @endif
        <link rel="alternate" href="{{$link}}" hreflang="{{$locale}}">
    @endforeach
@endif
