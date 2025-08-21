const CharacterCount = (config) => ({
    characterCount: 0,
    fieldId: config.fieldId,
    max: config.max,
    init() {
        const formField = document.querySelector(`#${this.fieldId.replaceAll('.', '\\.')}`);

        if (!formField) {
            console.error(`Character count not initiated: No formField found by selector: #${this.fieldId}`);
            return;
        }

        this.characterCount = this.removeHTML(formField.value).length;

        formField.addEventListener('input', () => {
            this.characterCount = this.removeHTML(formField.value).length;
        });
    },
    removeHTML(input) {
        let tmp = document.createElement('div');
        tmp.innerHTML = input;
        return tmp.textContent || tmp.innerText || '';
    },
});

export { CharacterCount as default };
