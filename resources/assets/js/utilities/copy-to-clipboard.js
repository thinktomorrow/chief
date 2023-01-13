import EventBus from './EventBus';

const CopyToClipboard = function (
    copyValueAttribute = 'data-copy-value',
    labelElementSelector = '[data-copy-label]',
    successContentAttribute = 'data-copy-success-content'
) {
    this.labelElementSelector = labelElementSelector;
    this.copyValueAttribute = copyValueAttribute;
    this.successContentAttribute = successContentAttribute;
};

CopyToClipboard.prototype.init = function (triggerElementSelector) {
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

CopyToClipboard.prototype._copyToClipboard = function () {
    const tempInput = document.createElement('input');

    tempInput.value = this.copyValue;

    document.body.appendChild(tempInput);

    tempInput.select();
    document.execCommand('copy');

    document.body.removeChild(tempInput);
};

CopyToClipboard.prototype._toggleSuccessState = function () {
    const originalTriggerLabelContent = this.triggerElement.innerHTML;

    this.triggerElement.innerHTML = this.triggerSuccessContent;

    setTimeout(() => {
        this.triggerElement.innerHTML = originalTriggerLabelContent;
    }, 2500);
};

const initCopyToClipboard = () => {
    const copyToClipboard = new CopyToClipboard();

    copyToClipboard.init('[data-copy-to-clipboard="bookmark"]');

    EventBus.subscribe('sidebarPanelActivated', () => {
        copyToClipboard.init('[data-copy-to-clipboard="bookmark"]');
    });
};

export { initCopyToClipboard as default };
