<x-chief::page>
    <x-slot name="header">
        <x-chief-form::form id="pagetitle" />
    </x-slot>

    <!-- can be either form or window display -->
{{--    <x-chief-form::form id='intro' />--}}
{{--    <x-chief-form::form id='intro2' />--}}

{{--    <x-chief::window>--}}
{{--        <div class="space-y-6">--}}
{{--            <x-chief-form::field tagged='intro' />--}}
{{--        </div>--}}
{{--    </x-chief::window>--}}

    @foreach($forms->filter(fn($form) => !in_array($form->getId(),['pagetitle']))->get() as $form)
        {{ $form->render() }}
    @endforeach


{{--    <x-chief::window.fields title="Algemeen" untagged />--}}
    <x-chief::fragments :owner="$model" />

    <x-slot name="sidebar">
        <x-chief::window.status />
        <x-chief::window.links />
{{--        <x-chief::window.fields title="Algemeen" tagged="sidebar" />--}}
{{--        <x-chief::window.fields title="SEO" tagged="seo" />--}}
    </x-slot>
</x-chief::page>
