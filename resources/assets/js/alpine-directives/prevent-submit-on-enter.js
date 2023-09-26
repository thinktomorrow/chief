// eslint-disable-next-line no-empty-pattern
const preventSubmitOnEnter = (el, {}, { cleanup }) => {
    const handler = (e) => {
        if (e.keyCode === 13) {
            e.preventDefault();
        }
    };

    el.addEventListener('keydown', handler);

    cleanup(() => {
        el.removeEventListener('click', handler);
    });
};

export { preventSubmitOnEnter as default };
