import Errors from './Errors';

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (const field in data) {
            if (Object.prototype.hasOwnProperty.call(data, field)) {
                this[field] = data[field];
            }
        }

        this.errors = new Errors();
    }

    /**
     * Fetch all relevant data for the form.
     */
    data() {
        const data = {};

        for (const property in this.originalData) {
            if (Object.prototype.hasOwnProperty.call(this.originalData, property)) {
                data[property] = this[property];
            }
        }

        return data;
    }

    add(key, value) {
        this.originalData[key] = value;
        this[key] = value;
    }

    /**
     * Reset the form fields.
     */
    reset() {
        for (const field in this.originalData) {
            if (Object.prototype.hasOwnProperty.call(this.originalData, field)) {
                this[field] = '';
            }
        }

        this.errors.clear();
    }

    post(url) {
        return this.submit('post', url);
    }

    put(url) {
        return this.submit('put', url);
    }

    patch(url) {
        return this.submit('patch', url);
    }

    delete(url) {
        return this.submit('delete', url);
    }

    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            window.axios[requestType](url, this.data())
                .then((response) => {
                    this.onSuccess(response.data);

                    resolve(response.data);
                })
                .catch((error) => {
                    this.onFail(error.response.data.errors);

                    reject(error.response.data);
                });
        });
    }

    // onSuccess(data) {
    //     // Default behaviour after each success update
    // }

    onFail(errors) {
        this.errors.record(errors);
    }
}

export default Form;
