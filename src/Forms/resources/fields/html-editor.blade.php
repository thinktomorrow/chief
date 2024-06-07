@php
    use Thinktomorrow\Chief\Forms\Livewire\LivewireFieldName;

    $extensions = [Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\HtmlExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ParagraphStylesExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\BoldExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\ItalicExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\UnderlineExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\BulletListExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\OrderedListExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\LinkExtension::class, Thinktomorrow\Chief\Forms\Fields\Editor\Extensions\CharacterCountExtension::class];
    $jsExtensions = [];

    foreach ($extensions as $extension) {
        foreach ($extension::jsExtensions() as $jsExtension) {
            $jsExtensions[] = $jsExtension;
        }
    }
@endphp

<div
    x-cloak
    x-data="{
        id: 'editor-{{ $getId($locale ?? null) }}',
        content: $refs.textarea.value,
        init() {
            window.TipTapEditors = window.TipTapEditors || {};

            const editorExtensions = window.TipTapExtensions
                .filter(extension => {{ str_replace('"', '\'', json_encode($jsExtensions)) }}.includes(extension.name))
                .map(extension => extension.extension);

            window.TipTapEditors[this.id] = new window.TipTapEditor({
                element: $refs.editor,
                extensions: [
                    window.TipTapDocument,
                    window.TipTapText,
                    window.TipTapParagraph,
                    ...editorExtensions,
                ],
                content: this.content,
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                },
            });
        },
        editor() {
            return window.TipTapEditors[this.id];
        }
    }"
    class="form-input-field rounded-lg p-0"
>
    <div class="flex items-start gap-3 border-b border-grey-100 bg-white px-3 py-2 first:rounded-t-lg">
        @foreach ($extensions as $extension)
            {!! $extension::renderButton(['locale' => $locale]) !!}
        @endforeach
    </div>

    <div x-ref="editor" class="prose prose-dark prose-spacing px-3 py-2"></div>

    <x-chief::input.textarea
        id="{{ $getId($locale ?? null) }}"
        name="{{ $getName($locale ?? null) }}"
        wire:model="{{ LivewireFieldName::get($getName($locale ?? null)) }}"
        x-ref="textarea"
        x-text="content"
        :attributes="$attributes->merge($getCustomAttributes())"
        v-pre="v-pre"
        cols="10"
        rows="10"
        class="hidden"
    >
        {{ $getActiveValue($locale ?? null) }}
    </x-chief::input.textarea>

    @foreach ($extensions as $extension)
        {!! $extension::renderFooter() !!}
    @endforeach
</div>

@pushOnce('custom-scripts')
<script src="{{ chief_cached_asset('chief-assets/back/js/editor.js') }}"></script>
@endPushOnce
