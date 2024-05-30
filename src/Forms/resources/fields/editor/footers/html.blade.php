<textarea
    x-ref="htmlTextarea"
    x-text="content"
    x-model="content"
    x-on:input.debounce.250ms="() => {
        editor().commands.setContent($el.value);
    }"
    v-pre="v-pre"
    cols="10"
    rows="10"
    class="hidden w-full px-3 py-2 text-sm text-white bg-black font-monospace last:rounded-b-lg"></textarea>
