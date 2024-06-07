import { Editor } from '@tiptap/core';
import Document from '@tiptap/extension-document';
import Text from '@tiptap/extension-text';
import Paragraph from '@tiptap/extension-paragraph';
import Heading from '@tiptap/extension-heading';
import Bold from '@tiptap/extension-bold';
import Underline from '@tiptap/extension-underline';
import Italic from '@tiptap/extension-italic';
import ListItem from '@tiptap/extension-list-item';
import BulletList from '@tiptap/extension-bullet-list';
import OrderedList from '@tiptap/extension-ordered-list';
import Link from '@tiptap/extension-link';

window.TipTapEditor = Editor;
window.TipTapDocument = Document;
window.TipTapText = Text;
window.TipTapParagraph = Paragraph;

window.TipTapExtensions = [
    { name: 'heading', extension: Heading },
    { name: 'bold', extension: Bold },
    { name: 'italic', extension: Italic },
    { name: 'underline', extension: Underline },
    { name: 'list_item', extension: ListItem },
    { name: 'bullet_list', extension: BulletList },
    { name: 'ordered_list', extension: OrderedList },
    { name: 'link', extension: Link.configure({ linkOnPaste: true }) },
];
