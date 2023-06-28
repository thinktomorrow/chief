const submissionsInProgress = [];

const Submissions = {
    isSubmitting(url) {
        return submissionsInProgress.includes(url);
    },
    addSubmission(url) {
        submissionsInProgress.push(url);
    },
    removeSubmission(url) {
        const index = submissionsInProgress.indexOf(url);
        if (index !== -1) {
            submissionsInProgress.splice(index, 1);
        }
    },
};

const Api = {
    get(url, successCallback, errorCallback, alwaysCallback, force = false) {
        if (!force && Submissions.isSubmitting(url)) {
            return;
        }

        Submissions.addSubmission(url);

        fetch(url)
            .then((response) => {
                if (response.status >= 500) {
                    throw Error(response);
                }
                return response.text();
            })
            .then((data) => {
                if (successCallback) successCallback(data, { method: 'get' });
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
                console.error(error);
            })
            .finally(() => {
                Submissions.removeSubmission(url);
                if (alwaysCallback) alwaysCallback();
            });
    },

    post(url, body, successCallback, errorCallback, alwaysCallback) {
        if (Submissions.isSubmitting(url)) {
            return;
        }

        Submissions.addSubmission(url);

        fetch(url, {
            method: 'POST',
            body,
            headers: {
                Accept: 'application/json',
            },
        })
            .then((response) => {
                if (response.status >= 500) {
                    throw Error(response);
                }
                return response.json();
            })
            .then((data) => {
                if (successCallback) successCallback(data, { method: 'post' });
            })
            .catch((error) => {
                if (errorCallback) errorCallback(error);
            })
            .finally(() => {
                Submissions.removeSubmission(url);
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
                if (form.classList.contains('is-submitting')) return;
                form.classList.add('is-submitting');

                // Show loading spinner when submitting a form if it exists
                const spinner = form.querySelector('[data-form-submit-spinner]');
                if (spinner) {
                    spinner.classList.remove('hidden');
                }

                if (this.method === 'get') {
                    const searchParams = new URLSearchParams(new FormData(this)).toString();
                    self.get(`${this.action}?${searchParams}`, successCallback, errorCallback, () => {
                        setTimeout(() => {
                            spinner.classList.add('hidden');
                            form.classList.remove('is-submitting');
                        }, 200);
                    });
                } else {
                    self.post(this.action, new FormData(this), successCallback, errorCallback, () => {
                        setTimeout(() => {
                            spinner.classList.add('hidden');
                            form.classList.remove('is-submitting');
                        }, 200);
                    });
                }
            });
        });
    },
};

export { Api as default };
