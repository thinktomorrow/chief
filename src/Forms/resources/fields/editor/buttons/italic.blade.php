<button type="button" x-on:click="
    () => {
        editor().chain().focus().toggleItalic().run()
    }
">
    <svg class="size-5 text-grey-900" viewBox="0 0 24 24" color="currentColor" fill="none">
        <path d="M12 4H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        <path d="M8 20L16 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        <path d="M5 20H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
    </svg>
</button>
