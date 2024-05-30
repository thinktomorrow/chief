<div class="px-3 py-1 bg-grey-100 last:rounded-b-lg" x-init="() => {
    editor().on('update', () => {
        $refs.characterCount.textContent = 'Aantal karakters: ' + $refs.editor.textContent.length;
    })
}">
    <span
        x-ref="characterCount"
        x-text="'Aantal karakters: ' + $refs.editor.textContent.length"
        class="text-xs font-monospace"></span>
</div>
