<div class="-window-x {{ $isFirstWindowItem ? '-mt-8' : null }} {{ $isLastWindowItem ? '-mb-8' : null }}">
    <div class="
        window-x py-8 border-grey-100
        {{ $fieldset->getTypeStyle() }} 
        {{ $isFirstWindowItem ? 'rounded-t-window' : null }}
        {{ $isLastWindowItem ? 'rounded-b-window' : null }}
        {{ $borderTop ? 'border-t' : null }}
        {{ $borderBottom ? 'border-b' : null }}
    ">
        <div class="space-y-6">
            @if($fieldset->getTitle() || $fieldset->getDescription())
                <div class="space-y-1">
                    @if($fieldset->getTitle())
                        <h3 class="text-lg font-semibold text-grey-900">{{ ucfirst($fieldset->getTitle()) }}</h4>
                    @endif

                    @if($fieldset->getDescription())
                        <div class="prose prose-dark prose-editor">
                            <p>{!! $fieldset->getDescription() !!}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div>
                <div class="row-start-start gutter-3">
                    @php 
                        $index = $loop->index;
                    @endphp

                    @foreach($fieldset->all() as $field)
                        @include('chief::manager.fields.form.field', ['autofocus' => (isset($index) && $index === 0)])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
