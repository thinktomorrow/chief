const Api = {
    get(url, successCallback, errorCallback, alwaysCallback) {
        fetch(url)
            .then((response) => response.text())
            .then((data) => {
                if (successCallback) successCallback(data, { method: 'get' });
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
                console.error(error);
            })
            .finally(() => {
                if (alwaysCallback) alwaysCallback();
            });
    },

    post(url, body, successCallback, errorCallback, alwaysCallback) {
        fetch(url, {
            method: 'POST',
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
            })
            .finally(() => {
                if (alwaysCallback) alwaysCallback();
            });
    },

    listenForFormSubmits(container, successCallback, errorCallback) {
        const self = this;
        const forms = Array.from(container.querySelectorAll('form'));

        if (forms.length < 1) return;

        forms.forEach((form) => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                // Avoid double submission - Check if already has been clicked
                if (form.classList.contains('is-submitting')) {
                    return;
                }
                form.classList.add('is-submitting');

                if (this.method === 'get') {
                    const searchParams = new URLSearchParams(new FormData(this)).toString();
                    self.get(`${this.action}?${searchParams}`, successCallback, errorCallback, () => {
                        form.classList.remove('is-submitting');
                    });
                } else {
                    self.post(this.action, new FormData(this), successCallback, errorCallback, () => {
                        form.classList.remove('is-submitting');
                    });
                }
            });
        });
    },
};

export { Api as default };
