const copyDirective = (el, { expression }, { evaluate }) => {
    const args = evaluate(expression);

    el.addEventListener('click', () => {
        navigator.clipboard.writeText(args.content);

        if (args.successContent) {
            window.dispatchEvent(
                new CustomEvent('create-notification', {
                    detail: {
                        type: 'success',
                        content: args.successContent,
                        duration: 5000,
                    },
                })
            );
        }
    });
};

export { copyDirective as default };
