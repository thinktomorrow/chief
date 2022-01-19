<div class="space-y-12">
    @if($form->getTitle())
        <div class="space-y-2">
            <p class="text-2xl display-base display-dark">
                {{ $form->getTitle() }}
            </p>
        </div>
    @endif

    <div class="space-y-6">
        {!! $slot !!}
    </div>
</div>
