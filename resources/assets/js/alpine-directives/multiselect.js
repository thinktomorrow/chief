import Choices from 'choices.js';

const multiselectDirective = (el, { expression }, { evaluate }) => {
    const args = evaluate(expression);

    el.choices = new Choices(args.selectEl, args.options);
};

export { multiselectDirective as default };
