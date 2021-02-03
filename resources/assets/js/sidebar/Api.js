export const Api = {

    get: function (url, container, callback, submitCallback) {
        fetch(url)
            .then(response => {
                return response.text()
            })
            .then(data => {
                container.innerHTML = data;

                // only mount Vue on our vue specific fields and not on the form element itself
                // so that the submit event still works. I know this is kinda hacky.
                new Vue({el: container.querySelector('[data-vue-fields]')});

                console.log('reloaded content');

                this.listenForFormSubmits(container, submitCallback);

                if (callback) callback();
            })
            .catch(error => {
                console.log(error);
            });
    },

    submit: function(method, url, body, successCallback, errorCallback) {
        fetch(url, { method: method, body: body, })
        .then(response => {
            return response.json()
        })
        .then(data => {
            if (successCallback) successCallback(data);
        })
        .catch(error => {
            if (errorCallback) errorCallback();
            console.error(error);
        });
    },

    listenForFormSubmits: function (container, callback) {
        const form = container.querySelector('form');
        let self = this;

        form.addEventListener('submit',  function(event) {
            event.preventDefault();

            self.submit(this.method, this.action, new FormData(this), callback);
        });
    }
}
