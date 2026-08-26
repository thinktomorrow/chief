@include('chief-menu::_formgroups.label')
@include('chief-menu::_formgroups.link')
@includeWhen(count($parents) > 0, 'chief-menu::_formgroups.parent')

@foreach ($layout->getComponents() as $component)
    @if ($component instanceof \Thinktomorrow\Chief\Forms\Layouts\Form)
        {{ $component->displayAsInlineForm()->render() }}
    @else
        {{ $component->render() }}
    @endif
@endforeach
