<?php
    // Avoid confusion in foreach loop below.
    $fragmentField = $field;

    $emptyFragment = null;
    $existingFragments = [];

    foreach($fragmentField->getFragments() as $i => $fragment) {
        $existingFragments[] = [
            'key' => $fragment->hasModelId() ? $fragment->getModelId() . '_'.mt_rand(1,9999) : 'key_' . mt_rand(1,9999), // unique key for vue for loops
            'modelId' => $fragment->hasModelId() ? $fragment->getModelId() : null,
            'modelIdInputName' => $fragment->getModelIdInputName(),
            'fields' => array_map(function(\Thinktomorrow\Chief\Fields\Types\Field $field){
                return [
                    'id' => $field->getDottedName(),
                    'label' => $field->getLabel(),
                    'content' => $field->render(),
                ];
            }, $fragment->getFields()->all()),
        ];

        if($i == 0) {
            $emptyFragment = [
                'modelId' => null,
                'modelIdInputName' => null,
                'fields' => array_map(function(\Thinktomorrow\Chief\Fields\Types\Field $field){
                    return [
                        'id' => $field->getDottedName(),
                        'label' => $field->getLabel(),
                        'content' => $field instanceof \Thinktomorrow\Chief\Fields\Types\MediaField
                            ? $field->value([])->render()
                            : $field->value('')->render(),
                    ];
                }, $fragment->getFields()->all()),
            ];
        }

    }

    $existingFragments = json_encode($existingFragments);

?>

<div id="{{ $fragmentField->getKey() }}">
    <fragments :existing-fragments="{{ $existingFragments }}" :empty-fragment="{{ json_encode($emptyFragment) }}" :errors="errors">
        <div slot-scope="{fragments, actions, errors}">
            <fragment v-for="(fragment,key) in fragments" :key="fragment.key" :item="fragment" :errors="errors">
                <div v-if="!deleted" slot-scope="{fragment, errors, deleteFragment, deleted}" class="mb-4 p-4 border">
                    <input type="hidden" :name="fragment.modelIdInputName" :value="fragment.modelId">
                    <fieldset v-for="(field,index) in fragment.fields" :key="fragment.key + '_fieldset_' + index">
                        <label for="field.id" v-text="field.label"></label>
                        <component :is="{template:field.content}" />
                    </fieldset>
                    <span @click="deleteFragment" class="cursor-pointer">DELETE</span>
                </div>
            </fragment>

            <span @click="actions.duplicateFragment" class="cursor-pointer">ADD</span>
        </div>
    </fragments>

</div>

{{--@push('custom-scripts-after-vue')--}}
{{--    <script>--}}

{{--        function triggerStuff(){--}}
{{--            window.App.doStuff();--}}
{{--        }--}}

{{--        ;(function(){--}}
{{--            function initFragment(key){--}}
{{--                var fragmentsContainer = document.getElementById(key),--}}
{{--                    fragmentsInnerContainer = fragmentsContainer.querySelector('[data-fragments]'),--}}
{{--                    addTrigger = fragmentsContainer.querySelector('[data-fragment-add]');--}}

{{--                function addFragment(){--}}
{{--                    var firstFragment = fragmentsContainer.querySelector('[data-fragment]'),--}}
{{--                        copiedFragment = firstFragment.cloneNode(true),--}}
{{--                        nextId = fragmentsInnerContainer.childElementCount;--}}

{{--                    copiedFragment.innerHTML = copiedFragment.innerHTML.replace(/\[0\]/g, '['+nextId+']'); // name attribute--}}
{{--                    copiedFragment.innerHTML = copiedFragment.innerHTML.replace(/\.0\./g, '.'+nextId+'.'); // id attribute--}}
{{--                    copiedFragment.innerHTML += '<span data-fragment-delete class="cursor-pointer">DELETE</span>';--}}

{{--                    // How to reinit slim....--}}
{{--                    triggerStuff();--}}

{{--                    // Since copiedFragment is a fieldset, we can loop over all the underlying fields and empty them--}}

{{--                    fragmentsInnerContainer.appendChild(copiedFragment);--}}

{{--                    Array.from(copiedFragment.elements).forEach(function(el){--}}
{{--                        el.value = null;--}}
{{--                    });--}}
{{--                    // console.log(copiedFragment.elements.reset());--}}

{{--                    registerListeners();--}}
{{--                }--}}

{{--                function removeFragment(event){--}}
{{--                    var fragment = event.target.closest('[data-fragment]');--}}

{{--                    // Do not remove last fragment--}}
{{--                    if(fragmentsContainer.querySelectorAll('[data-fragment]').length < 2){--}}
{{--                        return;--}}
{{--                    }--}}

{{--                    fragmentsInnerContainer.removeChild(fragment);--}}
{{--                }--}}

{{--                function registerListeners(){--}}
{{--                    addTrigger.addEventListener('click', addFragment);--}}

{{--                    var deleteTriggers = fragmentsInnerContainer.querySelectorAll('[data-fragment-delete]');--}}
{{--                    for(var i =0; i < deleteTriggers.length; i++) {--}}
{{--                        deleteTriggers[i].addEventListener('click', removeFragment);--}}
{{--                    }--}}
{{--                }--}}

{{--                registerListeners();--}}
{{--            }--}}

{{--            initFragment('{{$fragmentField->getKey()}}');--}}
{{--        })();--}}

{{--    </script>--}}
{{--@endpush--}}
