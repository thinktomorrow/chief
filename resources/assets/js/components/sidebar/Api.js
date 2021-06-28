const Api = {
    get(url, successCallback, errorCallback) {
        fetch(url)
            .then((response) => response.text())
            .then((data) => {
                if (successCallback) successCallback(data, { method: 'get' });
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
                console.error(error);
            });
    },

    post(url, body, successCallback, errorCallback, method = 'POST') {
        fetch(url, {
            method,
            body,
            headers: {
                Accept: 'application/json',
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (successCallback) successCallback(data, { method: 'post' });
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

                if (this.method === 'get') {
                    const searchParams = new URLSearchParams(new FormData(this)).toString();
                    self.get(`${this.action}?${searchParams}`, successCallback, errorCallback);
                } else {
                    self.post(this.action, new FormData(this), successCallback, errorCallback);
                }
            });
        });
    },
};

export { Api as default };
