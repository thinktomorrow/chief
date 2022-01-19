@if(isset($hasRedactorOptions) && $hasRedactorOptions())
        <div class="w-full external-editor-toolbar" id="js-external-editor-toolbar-{{ str_replace('.','_',$getId($locale ?? null)) }}"></div>
@endif

<div class="flex">
    @if($getPrepend())
        <div class="prepend-to-input">
            {!! $getPrepend($locale ?? null) !!}
        </div>
    @endif

    @if(isset($hasRedactorOptions) && $hasRedactorOptions())
        <div class="w-full {{ $hasPrepend() ? 'with-prepend' : null }} {{ $hasAppend() ? 'with-append' : null }}">
            @include('chief::editors.' . config('chief.editor', 'redactor') . '.input')
        </div>
    @else
        <input
                type="{{ $inputType ?? 'text' }}"
                name="{{ $getName($locale ?? null) }}"
                id="{{ $getId($locale ?? null) }}"
                placeholder="{{ $getPlaceholder($locale ?? null) }}"
                value="{{ $getActiveValue($locale ?? null) }}"
                class="{{ $hasPrepend() ? 'with-prepend' : null }} {{ $hasAppend() ? 'with-append' : null }}"
                {{ $hasAutofocus() ? 'autofocus' : '' }}
        >
    @endif

    @if($getAppend())
        <div class="append-to-input">
            {!! $getAppend($locale ?? null) !!}
        </div>
    @endif
</div>
