/**
 * Trigger form submit with a button outside of the form.
 * note that this is scanned on pageload. e.g.
 * <button data-submit-form="formID">click me to submit</button>
 */
function listen(selector, container) {
    const el = container || document;

    const triggers = el.querySelectorAll(selector);

    for (let i = 0; i < triggers.length; i++) {
        triggers[i].addEventListener(
            'click',
            function () {
                document.getElementById(this.getAttribute('data-submit-form')).submit();
            },
            false
        );
    }
}

export default { listen };
