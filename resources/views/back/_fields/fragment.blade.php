<?php
    // Avoid confusion in foreach loop below.
    $fragmentField = $field;

    // The list of duplicatable fields in the order of appearance.
    // This reference will also work as an DOM identifier for each field
    $duplicatableFields = [];

    foreach($fragmentField->getFragments() as $i => $fragment){
        $duplicatableFields[$fragment->getKey()] = array_map(function(\Thinktomorrow\Chief\Fields\Types\Field $field) use($model, $fragment){
            return (new \Thinktomorrow\Chief\Fields\FieldReference($model->managedModelKey(), $field->getKey(), $fragment->getKey()))->toArray();
        }, $fragment->getFields()->all());
    }
?>

<alert type="error" class="p-4">😱 EXPERIMENTELE COMPONENT <br><br>Onderstaande blokken bevatten nieuwe functionaliteit in chief. Het gaat om het dynamisch toevoegen van veldjes. Een bug ontdekt? Team dev wants to know! <br><br> Met dank, <br>Het tt development team.</alert>
<div id="{{ $fragmentField->getKey() }}">
    <div data-fragments>
        @foreach($fragmentField->getFragments() as $i => $fragment)
            <fieldset id="{{ $fragment->getKey().'-'.$i }}" data-fragment="{{ $fragment->getKey() }}" class="mb-4 p-4 border">
                @if($fragment->hasModelId())
                    <input type="hidden" name="{{ $fragment->getModelIdInputName() }}" value="{{ $fragment->getModelId() }}">
                @endif
                @foreach($fragment->getFields() as $field)
                    <label for="{{ $field->getDottedName() }}">{{ $field->getLabel() }}</label>
                    <div data-fragment-field="{{ $field->getKey() }}">
                        {!! $field->render() !!}
                    </div>
                @endforeach
                @if($i > 0)
                    <span data-fragment-delete class="cursor-pointer">DELETE</span>
                @endif
            </fieldset>
        @endforeach
    </div>

    <span data-fragment-add class="cursor-pointer">ADD</span>
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
                        fragmentKey = firstFragment.dataset.fragment,
                        copiedFragment = firstFragment.cloneNode(true),
                        nextId = fragmentsInnerContainer.childElementCount,
                        fragmentId = copiedFragment.id + nextId;

                    copiedFragment.id = copiedFragment.id + nextId;
                    copiedFragment.innerHTML += '<span data-fragment-delete class="cursor-pointer">DELETE</span>';

                    Array.from(copiedFragment.elements).forEach(function(el){
                        el.value = null;
                    });

                    fragmentsInnerContainer.appendChild(copiedFragment);
                    registerListeners();

                    let newFragment = fragmentsInnerContainer.querySelector('#' + fragmentId);

                    for(key in duplicatableFields[fragmentKey]) {
                        const references = duplicatableFields[fragmentKey][key];

                        axios.get('/admin/api/field', {
                            params: references,
                            responseType: 'json',
                        }).then(function(response){

                            let fieldContainer = newFragment.querySelector('[data-fragment-field="' + references.fieldKey + '"]');

                            fieldContainer.innerHTML = response.data.data;
                            fieldContainer.innerHTML = fieldContainer.innerHTML.replace(/\[0\]/g, '['+nextId+']'); // name attribute
                            fieldContainer.innerHTML = fieldContainer.innerHTML.replace(/\.0\./g, '.'+nextId+'.'); // id attribute

                            new Vue({el: fieldContainer});

                            // Reinit wysiwyg fields
                            $R('[data-editor]');
                        });
                    }
                }

                function removeFragment(event){
                    var fragment = event.target.closest('[data-fragment]');

                    // Do not remove last fragment
                    if(fragmentsContainer.querySelectorAll('[data-fragment]').length < 2){
                        return;
                    }

                    fragmentsInnerContainer.removeChild(fragment);
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

            initFragment('{{$fragmentField->getKey()}}', JSON.parse('@json($duplicatableFields)'));
        })();

    </script>
@endpush
