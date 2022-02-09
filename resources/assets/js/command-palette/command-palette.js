import Api from '../components/sidebar/Api';

const initCommandPalette = () => {
    const container = document.querySelector('#command-palette');
    const searchElement = container.querySelector('#search');
    const resultElement = container.querySelector('#result');

    searchElement.addEventListener('input', () => {
        Api.get(`/admin/search/${searchElement.value}`, (data) => {
            const DOM = document.createElement('div');
            DOM.innerHTML = data;
            resultElement.innerHTML = DOM.innerHTML;
        });
    });

    document.addEventListener('keydown', (e) => {
        // Register command palette toggle keybind
        if (e.metaKey && e.keyCode === 75) {
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                searchElement.focus();
            } else {
                container.classList.add('hidden');
            }
        }

        // Add all element selectors here that should be focusable
        const allTargets = [searchElement, ...Array.from(container.querySelectorAll('[href]'))];

        if (e.keyCode === 40 || (e.ctrlKey && e.keyCode === 74)) {
            const targetIndex = allTargets.indexOf(e.target);

            if (targetIndex === allTargets.length - 1) {
                allTargets[0].focus();
            } else {
                allTargets[targetIndex + 1].focus();
            }
        }

        if (e.keyCode === 38 || (e.ctrlKey && e.keyCode === 75)) {
            const targetIndex = allTargets.indexOf(e.target);

            if (targetIndex === 0) {
                allTargets[allTargets.length - 1].focus();
            } else {
                allTargets[targetIndex - 1].focus();
            }
        }
    });
};

export { initCommandPalette as default };
