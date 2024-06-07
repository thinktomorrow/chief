<button type="button" x-on:click="
    () => {
        editor().chain().focus().toggleBulletList().run()
    }
">
    <svg class="size-5 text-grey-900" viewBox="0 0 24 24" color="currentColor" fill="none">
        <path d="M8 5L20 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        <path d="M4 5H4.00898" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M4 12H4.00898" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M4 19H4.00898" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M8 12L20 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
        <path d="M8 19L20 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
    </svg>
</button>
