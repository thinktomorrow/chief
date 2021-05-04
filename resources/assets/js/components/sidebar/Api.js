const Api = {
    get(url, container, successCallback, errorCallback) {
        fetch(url)
            .then((response) => response.text())
            .then((data) => {
                if (successCallback) successCallback(data);
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
                console.error(error);
            });
    },

    submit(method, url, body, successCallback, errorCallback) {
        fetch(url, {
            method,
            body,
            headers: {
                Accept: 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (successCallback) successCallback(data);
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
            });
    },

    listenForFormSubmits(container, successCallback, errorCallback) {
        const self = this;
        const forms = Array.from(container.querySelectorAll('form'));

        if (forms.length < 1) return;

        forms.forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                self.submit(this.method, this.action, new FormData(this), successCallback, errorCallback);
            });
        });
    },
};

export { Api as default };
