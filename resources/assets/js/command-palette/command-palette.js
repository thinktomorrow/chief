import Api from '../components/sidebar/Api';

const initCommandPalette = () => {
    const container = document.querySelector('#command-palette');
    const term = container.querySelector('#search');
    const resultElement = container.querySelector('#result');

    term.addEventListener('input', () => {
        Api.get(`/admin/search/${term.value}`, (data) => {
            const DOM = document.createElement('div');
            DOM.innerHTML = data;
            resultElement.innerHTML = DOM.innerHTML;
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.metaKey && e.keyCode === 75) {
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                term.focus();
            } else {
                container.classList.add('hidden');
            }
        }
    });

    document.addEventListener('keydown', (e) => {
        // Add all element selectors here that should be focusable
        const allTabTargets = Array.from(container.querySelectorAll('[href]'));

        if (e.shiftKey && e.keyCode === 9) {
            if (allTabTargets.length === 0) {
                e.preventDefault();
                term.focus();
            } else if (term === e.target) {
                e.preventDefault();
                allTabTargets[allTabTargets.length - 1].focus();
            }
        } else if (e.keyCode === 9) {
            if (allTabTargets[allTabTargets.length - 1] === e.target || allTabTargets.length === 0) {
                e.preventDefault();
                term.focus();
            }
        }
    });
};

export { initCommandPalette as default };
