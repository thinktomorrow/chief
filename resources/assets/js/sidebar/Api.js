export const Api = {

    get: function (url, container, successCallback, errorCallback) {
        fetch(url)
            .then(response => {
                return response.text()
            })
            .then(data => {
                if (successCallback) successCallback(data);
            })
            .catch(error => {
                if (errorCallback) errorCallback(error);
                console.error(error);
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
            if (errorCallback) errorCallback(error);
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
