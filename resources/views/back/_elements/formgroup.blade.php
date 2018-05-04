<section class="row formgroup stack gutter-l">
    <div class="column-4">
        @if(isset($label))
            <h2 class="formgroup-label">{{ $label }}</h2>
        @endif
        @if(isset($description))
            <p class="caption">{{ $description }}</p>
        @endif
    </div>
    <div class="input-group column-8 {{ $errors->has($field) ? 'error' : '' }}">
        {{ $slot }}
        @if($errors->has($field))
            <span class="caption">{{ $errors->first($field) }}</span>
        @endif
    </div>
</section>