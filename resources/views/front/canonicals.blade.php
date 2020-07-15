@php
    $path = request()->path() == '/' ? '' : request()->path();
    $model = $model ?? $page;
    $base_url = config('app.url');
@endphp
<link rel="canonical" href="{{ $base_url . '/' . $path }}" />
@if($locales)
    @foreach($locales as $locale)
        @php

            try{
                $localized_path = Thinktomorrow\Chief\Urls\MemoizedUrlRecord::findByModel($model, $locale)->slug;
            }catch(Thinktomorrow\Chief\Urls\UrlRecordNotFound $ex){
                continue;
            }

            $link = $base_url . '/' . $locale . ($localized_path == '/' ? '' :  '/'. $localized_path);
        @endphp
        @if(!$link) @continue @endif
        <link rel="alternate" href="{{$link}}" hreflang="{{$locale}}">
    @endforeach
@endif
