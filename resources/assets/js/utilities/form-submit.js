/**
 * Trigger form submit with a button outside of the form.
 * note that this is scanned on pageload. e.g.
 * <button data-submit-form="formID">click me to submit</button>
 */
const triggers = document.querySelectorAll('[data-submit-form]');

for(let i = 0;i<triggers.length;i++) {
    triggers[i].addEventListener('click',function(){
        document.getElementById(this.getAttribute('data-submit-form')).submit();
    }, false);
}
