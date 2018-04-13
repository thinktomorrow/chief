class Errors {
	/**
	 * Create a new Errors instance.
	 */
	constructor() {
		this.errors = {};
	}


	/**
	 * Determine if an errors exists for the given field.
	 *
	 * @param {string} field
	 */
	has(field) {
        var results = {};
        for (var property in this.errors) {
            if (this.errors.hasOwnProperty(property) && property.toString().startsWith(field)) {
                results[property] = this.errors[property];
            }
        }

        return (Object.keys(results).length === 1 && field in results);
	}


	/**
	 * Determine if we have any errors.
	 */
	any() {
		return Object.keys(this.errors).length > 0;
	}


	/**
	 * Retrieve the error message for a field and returns
	 * each field that starts with this string.
	 *
	 * @param {string} field
	 */
	get(field) {
		var results = {};
		for (var property in this.errors) {
			if (this.errors.hasOwnProperty(property) && property.toString().startsWith(field)) {
				results[property] = this.errors[property];
			}
		}

		if(Object.keys(results).length === 1 && field in results)
		{
			return results[field][0];
		}

		return results;
	}


	/**
	 * Record the new errors.
	 *
	 * @param {object} errors
	 */
	record(errors) {
		this.errors = errors;
		return this;
	}


	/**
	 * Clear one or all error fields.
	 *
	 * @param {string|null} field
	 */
	clear(field) {
		let errors = this.errors;

		if(field && field.includes('[') ){
			delete errors[field.replace(/\[/g, '.').replace(/\]/g,'')];

			this.errors = {};
			this.errors = errors;
			return;
		}else if (field) {
			delete errors[field];

			this.errors = {};
			this.errors = errors;
			return;
		}
		this.errors = {};
	}
}

export default Errors;