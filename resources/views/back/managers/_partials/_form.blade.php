@if($fieldArrangement->hasTabs())
    <tabs>
        @foreach($fieldArrangement->tabs() as $tab)
            <tab name="{{ $tab->title() }}">
                @if($tab->hasView())
                    @include($tab->view(), ['tab' => $tab])
                @else
                    @foreach($tab->fields() as $field)
                        {!! $manager->renderField($field) !!}
                    @endforeach
                @endif
            </tab>
        @endforeach
    </tabs>
@else
    @foreach($fieldArrangement->fields() as $field)
        {!! $manager->renderField($field) !!}
    @endforeach
@endif



