const handler = (e) => {
    if (e.keyCode === 13) {
        e.preventDefault();
    }
};

const preventSubmitOnEnter = (el, _binding, { cleanup }) => {
    el.addEventListener('keydown', handler);

    cleanup(() => {
        el.removeEventListener('keydown', handler);
    });
};

export default preventSubmitOnEnter;
