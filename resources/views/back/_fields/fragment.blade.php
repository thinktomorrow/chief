<?php
    // Avoid confusion in foreach loop below.
    $fragmentField = $field;
?>

<div id="{{ $fragmentField->getKey() }}">
    <div data-fragments>
        @foreach($fragmentField->getFragments() as $i => $fragment)
            <fieldset id="{{ $fragment->getKey().'-'.$i }}" data-fragment="{{ $fragment->getKey() }}" class="border bg-white border-grey-100 rounded mb-4">
                <div class="flex squished items-center bg-grey-100" style="margin-bottom:-3px;">
                    <span data-fragment-label>{{ $fragmentField->getFragmentLabel() }} {{ $i+1 }}</span>
                    <div data-fragment-delete class="squished-s text-error hover:bg-grey-50 rounded center-y cursor-pointer {{ (count($fragmentField->getFragments()) == 1) ? 'hidden' : '' }}" style="margin-left:auto;">
                        <svg class="mr-2" width="18" height="18"><use data-v-3997f6a0="" xlink:href="#trash"></use></svg>
                        <span>Verwijder {{ $fragmentField->getFragmentLabel() }}</span>
                    </div>
                </div>
                <div class="inset">
                    @if($fragment->hasModelId())
                        <input data-fragment-no-duplicate type="hidden" name="{{ $fragment->getModelIdInputName() }}" value="{{ $fragment->getModelId() }}">
                    @endif
                    @foreach($fragment->getFields() as $field)
                    <div class="mb-4">
                        <label for="{{ $field->getDottedName() }}">{{ $field->getLabel() }}</label>
                        <div data-fragment-field="{{ $field->getKey() }}">
                            {!! $field->render() !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach
    </div>
    <div class="flex justify-center items-center w-full h-8 center-y relative z-10">
        <span data-fragment-add class="block menu-trigger bg-secondary-50 rounded-full cursor-pointer mx-auto hover:text-secondary-600">
            <svg width="24" height="24" class="fill-current"><use xlink:href="#plus"/></svg>
        </span>
    </div>
</div>

@push('custom-scripts-after-vue')
    <script>

        // TODO: place the generic logic in a js file
        ;(function(){
            function initFragment(key, duplicatableFields){
                var fragmentsContainer = document.getElementById(key),
                    fragmentsInnerContainer = fragmentsContainer.querySelector('[data-fragments]'),
                    addTrigger = fragmentsContainer.querySelector('[data-fragment-add]');

                function addFragment(){

                    var firstFragment = fragmentsContainer.querySelector('[data-fragment]'),
                        copiedFragment = firstFragment.cloneNode(true),
                        nextId = fragmentsInnerContainer.childElementCount,
                        fragmentId = copiedFragment.id + nextId;

                    copiedFragment.id = copiedFragment.id + nextId;

                    // Display the delete button
                    copiedFragment.querySelector('[data-fragment-delete]').classList.remove('hidden');

                    // Labeling of fragment block
                    var fragmentLabel = copiedFragment.querySelector('[data-fragment-label]');
                    fragmentLabel.innerHTML = fragmentLabel.innerHTML.replace(/1/, nextId+1);

                    Array.from(copiedFragment.querySelectorAll('[data-fragment-no-duplicate]')).forEach(function(el){ el.remove()});

                    fragmentsInnerContainer.appendChild(copiedFragment);
                    registerListeners();

                    let newFragment = fragmentsInnerContainer.querySelector('#' + fragmentId);

                    for(key in duplicatableFields) {
                        const fieldHtml = duplicatableFields[key];

                        let fieldContainer = newFragment.querySelector('[data-fragment-field="' + key + '"]');

                        fieldContainer.innerHTML = fieldHtml;
                        fieldContainer.innerHTML = fieldContainer.innerHTML.replace(/\[0\]/g, '['+nextId+']'); // name attribute
                        fieldContainer.innerHTML = fieldContainer.innerHTML.replace(/\.0\./g, '.'+nextId+'.'); // id attribute

                        new Vue({el: fieldContainer});

                        // Reinit wysiwyg fields
                        $R('[data-editor]');
                    }
                }

                function removeFragment(event){
                    var fragment = event.target.closest('[data-fragment]');

                    // Do not remove last fragment
                    if(fragmentsContainer.querySelectorAll('[data-fragment]').length < 2){
                        return;
                    }

                    fragmentsInnerContainer.removeChild(fragment);

                    if(fragmentsContainer.querySelectorAll('[data-fragment]').length == 1){
                        fragmentsInnerContainer.querySelector('[data-fragment-delete]').classList.add('hidden')
                    }
                }

                function registerListeners(){
                    addTrigger.addEventListener('click', addFragment);

                    var deleteTriggers = fragmentsInnerContainer.querySelectorAll('[data-fragment-delete]');
                    for(var i =0; i < deleteTriggers.length; i++) {
                        deleteTriggers[i].addEventListener('click', removeFragment);
                    }
                }

                registerListeners();
            }

            initFragment('{{$fragmentField->getKey()}}', @json($fragmentField->getDuplicatableFields()));
        })();

    </script>
@endpush
