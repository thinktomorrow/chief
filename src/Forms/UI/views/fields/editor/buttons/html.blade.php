<x-chief-form::editor.button
    x-ref="showHtml"
    x-on:click="
        () => {
            $refs.editor.classList.add('hidden')
            $refs.htmlTextarea.style.display = 'block'
            $refs.showHtml.classList.add('hidden')
            $refs.hideHtml.classList.remove('hidden')
        }
    "
>
    <x-chief-form::editor.icon.source-code />
</x-chief-form::editor.button>

<x-chief-form::editor.button
    x-ref="hideHtml"
    x-on:click="
        () => {
            $refs.editor.classList.remove('hidden')
            $refs.htmlTextarea.style.removeProperty('display')
            $refs.hideHtml.classList.add('hidden')
            $refs.showHtml.classList.remove('hidden')
        }
    "
    class="hidden bg-grey-100"
>
    <x-chief-form::editor.icon.source-code />
</x-chief-form::editor.button>
