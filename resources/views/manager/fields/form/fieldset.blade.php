<div>
    <div class="-my-8 -window-x">
        <div class="window-x py-8 space-y-6 {{ $fieldSet->getTypeStyle() }}">
            @if($fieldSet->getTitle() || $fieldSet->getDescription())
                <div class="space-y-1">
                    @if($fieldSet->getTitle())
                        <h3 class="text-lg font-semibold text-grey-900">{{ ucfirst($fieldSet->getTitle()) }}</h4>
                    @endif

                    @if($fieldSet->getDescription())
                        <div class="prose prose-dark prose-editor">
                            <p>{!! $fieldSet->getDescription() !!}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div>
                <div class="row-start-start gutter-3">
                    @foreach($fieldSet->all() as $field)
                        @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
