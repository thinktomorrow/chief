import EventBus from './EventBus';

const FragmentBookmark = function (
    copyValueAttribute = 'data-copy-value',
    labelElementSelector = '[data-copy-label]',
    successContentAttribute = 'data-copy-success-content'
) {
    this.labelElementSelector = labelElementSelector;
    this.copyValueAttribute = copyValueAttribute;
    this.successContentAttribute = successContentAttribute;
};

FragmentBookmark.prototype.init = function () {
    this._initCopyToClipboard();
    this._initToggleEditField();
};

FragmentBookmark.prototype._initCopyToClipboard = function (
    triggerElementSelector = '[data-copy-to-clipboard="bookmark"]'
) {
    this.triggerElement = document.querySelector(triggerElementSelector);

    if (!this.triggerElement) return;

    this.copyValue = this.triggerElement.getAttribute(this.copyValueAttribute);
    this.triggerLabelElement = this.triggerElement.querySelector(this.labelElementSelector);
    this.triggerSuccessContent = this.triggerElement.getAttribute(this.successContentAttribute);

    this.triggerElement.addEventListener('click', () => {
        this._copyToClipboard();
        this._toggleSuccessState();
    });
};

FragmentBookmark.prototype._initToggleEditField = function () {
    const fragmentBookmarkLabel = document.querySelector('[data-fragment-bookmark-label]');
    const fragmentBookmarkForm = document.querySelector('[data-fragment-bookmark-form]');
    const fragmentBookmarkInput = document.querySelector('[data-fragment-bookmark-input]');
    const fragmentBookmarkEditButton = document.querySelector('[data-fragment-bookmark-edit-button]');
    const fragmentBookmarkUndoButton = document.querySelector('[data-fragment-bookmark-undo-button]');
    const fragmentBookmarkConfirmButton = document.querySelector('[data-fragment-bookmark-confirm-button]');

    if (
        !fragmentBookmarkLabel ||
        !fragmentBookmarkForm ||
        !fragmentBookmarkInput ||
        !fragmentBookmarkEditButton ||
        !fragmentBookmarkUndoButton ||
        !fragmentBookmarkConfirmButton
    ) {
        return;
    }

    [fragmentBookmarkEditButton, fragmentBookmarkUndoButton, fragmentBookmarkConfirmButton].forEach((button) => {
        button.addEventListener('click', () => {
            fragmentBookmarkLabel.classList.toggle('hidden');
            fragmentBookmarkForm.classList.toggle('hidden');
            fragmentBookmarkEditButton.classList.toggle('hidden');
            fragmentBookmarkUndoButton.classList.toggle('hidden');
            fragmentBookmarkConfirmButton.classList.toggle('hidden');
        });
    });

    fragmentBookmarkUndoButton.addEventListener('click', () => {
        fragmentBookmarkInput.value = fragmentBookmarkInput.defaultValue;
        fragmentBookmarkLabel.innerHTML = fragmentBookmarkInput.defaultValue;
    });

    fragmentBookmarkConfirmButton.addEventListener('click', () => {
        fragmentBookmarkLabel.innerHTML = fragmentBookmarkInput.value;
    });
};

FragmentBookmark.prototype._copyToClipboard = function () {
    const tempInput = document.createElement('input');

    tempInput.value = this.copyValue;

    document.body.appendChild(tempInput);

    tempInput.select();
    document.execCommand('copy');

    document.body.removeChild(tempInput);
};

FragmentBookmark.prototype._toggleSuccessState = function () {
    const originalTriggerLabelContent = this.triggerElement.innerHTML;

    this.triggerElement.innerHTML = this.triggerSuccessContent;

    setTimeout(() => {
        this.triggerElement.innerHTML = originalTriggerLabelContent;
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
