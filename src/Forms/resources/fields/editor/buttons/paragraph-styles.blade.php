<div class="w-5 h-5">
    <button type="button" id="tiptap-header-paragraph-styles">
        <svg class="w-5 h-5 text-grey-900" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
            <path d="M208,40H96a64,64,0,0,0,0,128h40v40a8,8,0,0,0,16,0V56h24V208a8,8,0,0,0,16,0V56h16a8,8,0,0,0,0-16ZM136,152H96a48,48,0,0,1,0-96h40Z"></path>
        </svg>
    </button>

    <x-chief::dropdown trigger="#tiptap-header-paragraph-styles">
        <button
            type="button"
            x-on:click="() => {
                editor().chain().focus().setHeading({ level: 1 }).run();
                open = false;
            }"
            class="inline-flex items-start gap-2 px-3 py-1.5 hover:bg-grey-100 text-grey-900 leading-5">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                <path d="M152,56V176a8,8,0,0,1-16,0V124H48v52a8,8,0,0,1-16,0V56a8,8,0,0,1,16,0v52h88V56a8,8,0,0,1,16,0Zm75.77,49a8,8,0,0,0-8.21.39l-24,16a8,8,0,1,0,8.88,13.32L216,127V208a8,8,0,0,0,16,0V112A8,8,0,0,0,227.77,105Z"></path>
            </svg>
            Heading 1
        </button>

        <button
            type="button"
            x-on:click="() => {
                editor().chain().focus().setHeading({ level: 2 }).run();
                open = false;
            }"
            class="inline-flex items-start gap-2 px-3 py-1.5 hover:bg-grey-100 text-grey-900 leading-5">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                <path d="M152,56V176a8,8,0,0,1-16,0V124H48v52a8,8,0,0,1-16,0V56a8,8,0,0,1,16,0v52h88V56a8,8,0,0,1,16,0Zm88,144H208l33.55-44.74a32,32,0,1,0-55.73-29.93,8,8,0,1,0,15.08,5.34,16.28,16.28,0,0,1,2.32-4.3,16,16,0,1,1,25.54,19.27L185.6,203.2A8,8,0,0,0,192,216h48a8,8,0,0,0,0-16Z"></path>
            </svg>
            Heading 2
        </button>

        <button
            type="button"
            x-on:click="() => {
                editor().chain().focus().setParagraph().run();
                open = false;
            }"
            class="inline-flex items-start gap-2 px-3 py-1.5 hover:bg-grey-100 text-grey-900 leading-5">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256">
                <path d="M208,56V88a8,8,0,0,1-16,0V64H136V192h24a8,8,0,0,1,0,16H96a8,8,0,0,1,0-16h24V64H64V88a8,8,0,0,1-16,0V56a8,8,0,0,1,8-8H200A8,8,0,0,1,208,56Z"></path>
            </svg>
            Paragraph
        </button>
    </x-chief::dropdown>
</div>
