import EventBus from './EventBus';

const FragmentBookmark = function () {};

FragmentBookmark.prototype.init = function (
    labelSelector = '[data-fragment-bookmark-label]',
    formSelector = '[data-fragment-bookmark-form]',
    inputSelector = '[data-fragment-bookmark-input]',
    editButtonSelector = '[data-fragment-bookmark-edit-button]',
    undoButtonSelector = '[data-fragment-bookmark-undo-button]',
    confirmButtonSelector = '[data-fragment-bookmark-confirm-button]',
    externalLinkButtonSelector = '[data-fragment-bookmark-external-link-button]',
    copyButtonSelector = '[data-fragment-bookmark-copy-button]'
) {
    this.label = document.querySelector(labelSelector);
    this.form = document.querySelector(formSelector);
    this.input = document.querySelector(inputSelector);
    this.editButton = document.querySelector(editButtonSelector);
    this.undoButton = document.querySelector(undoButtonSelector);
    this.confirmButton = document.querySelector(confirmButtonSelector);
    this.externalLinkButton = document.querySelector(externalLinkButtonSelector);
    this.copyButton = document.querySelector(copyButtonSelector);

    this._initCopyToClipboard();
    this._initToggleEditField();
};

FragmentBookmark.prototype._initCopyToClipboard = function (
    copyValueAttribute = 'data-copy-value',
    copyLabelElementSelector = '[data-copy-label]',
    successContentAttribute = 'data-copy-success-content'
) {
    this.copyLabelElementSelector = copyLabelElementSelector;
    this.copyValueAttribute = copyValueAttribute;
    this.successContentAttribute = successContentAttribute;

    if (!this.copyButton) return;

    this.triggerLabelElement = this.copyButton.querySelector(this.copyLabelElementSelector);
    this.triggerSuccessContent = this.copyButton.getAttribute(this.successContentAttribute);

    this.copyButton.addEventListener('click', () => {
        this._copyToClipboard();
        this._toggleSuccessState();
    });
};

FragmentBookmark.prototype._initToggleEditField = function () {
    if (
        !this.label ||
        !this.form ||
        !this.input ||
        !this.editButton ||
        !this.undoButton ||
        !this.confirmButton ||
        !this.externalLinkButton ||
        !this.copyButton
    ) {
        return;
    }

    [this.editButton, this.undoButton, this.confirmButton].forEach((button) => {
        button.addEventListener('click', () => {
            this.label.classList.toggle('hidden');
            this.form.classList.toggle('hidden');
            this.editButton.classList.toggle('hidden');
            this.undoButton.classList.toggle('hidden');
            this.confirmButton.classList.toggle('hidden');
        });
    });

    this.undoButton.addEventListener('click', () => {
        const hash = `#${this.input.defaultValue}`;
        const link = this.externalLinkButton.href.split('#')[0] + hash;

        this.label.innerHTML = hash;
        this.externalLinkButton.href = link;
        this.copyButton.dataset.copyValue = link;

        this.input.value = this.input.defaultValue;
    });

    this.confirmButton.addEventListener('click', () => {
        const hash = `#${this.input.value}`;
        const link = this.externalLinkButton.href.split('#')[0] + hash;

        this.label.innerHTML = hash;
        this.externalLinkButton.href = link;
        this.copyButton.dataset.copyValue = link;
    });
};

FragmentBookmark.prototype._copyToClipboard = function () {
    const tempInput = document.createElement('input');

    tempInput.value = this.copyButton.getAttribute(this.copyValueAttribute);

    document.body.appendChild(tempInput);

    tempInput.select();
    document.execCommand('copy');

    document.body.removeChild(tempInput);
};

FragmentBookmark.prototype._toggleSuccessState = function () {
    const originalTriggerLabelContent = this.copyButton.innerHTML;

    this.copyButton.innerHTML = this.triggerSuccessContent;

    setTimeout(() => {
        this.copyButton.innerHTML = originalTriggerLabelContent;
    }, 2500);
};

const initCopyToClipboard = () => {
    const fragmentBookmark = new FragmentBookmark();

    fragmentBookmark.init();

    EventBus.subscribe('sidebarPanelActivated', () => {
        fragmentBookmark.init();
    });
};

export { initCopyToClipboard as default };
