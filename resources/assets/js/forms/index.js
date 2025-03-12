import Sidebar from './sidebar/Sidebar';
import Forms from './Forms';
import FormSubmit from './utilities/form-submit';
import initRepeatFields from './fields/init-repeat-fields';
import { initConditionalFields } from './conditional-fields/init-conditional-fields';
import initFormNotifications from './utilities/form-notifications';
import initCharacterCount from './utilities/character-count';

document.addEventListener('DOMContentLoaded', () => {
    new Forms(document.getElementById('content'), new Sidebar()).load(document);

    initRepeatFields();
    initConditionalFields();
    initFormNotifications();
    initCharacterCount();

    FormSubmit.listen('[data-submit-form]');
});

document.addEventListener('fragment-dialog-opened', () => {
    // Get DOM element

    console.log('sisisi in de main!!!');

    // Next tick my friend... next tick
    setTimeout(() => {
        initRepeatFields();
        initConditionalFields();
        initFormNotifications();
        initCharacterCount();
    }, 0);
});
