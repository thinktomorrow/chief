/**
 * Default Chief upload script for redactor wysiwyg.
 * This should be added to the redactor options:
 * {
 *     imageUpload: chiefRedactorImageUpload,
 * }
 *
 * @param formData
 * @param files
 * @param event
 * @param upload
 */
window.chiefRedactorImageUpload = function (uploadUrl) {
    return function (formData, files, event, upload) {
        return new Promise(function (resolve, reject) {
            function fileToDataURL(file) {
                const reader = new FileReader();
                return new Promise(function (resolve, reject) {
                    reader.onload = function (event) {
                        resolve({ data: reader.result, filename: file.name });
                    };
                    reader.readAsDataURL(file);
                });
            }

            function readAllAsDataURL(files) {
                return Promise.all(Array.from(files).map(fileToDataURL));
            }

            var token = document.head.querySelector('meta[name="csrf-token"]');

            readAllAsDataURL(files).then(function (payload) {
                window.axios
                    .post(
                        uploadUrl,
                        { files: payload },
                        {
                            headers: {
                                'X-CSRF-TOKEN': token.content,
                            },
                        }
                    )
                    .then((response) => {
                        // PostsizeTooLarge is returned as 200 instead of 419 to meet the redactor requirements
                        if (typeof response.data == 'string' && response.data.includes('POST Content-Length')) {
                            reject({ error: true, message: 'De afbeelding is te groot en is niet opgeladen.' });
                        }

                        resolve(response.data);
                    })
                    .catch((error) => {
                        console.error(error);
                        reject(error);
                    });
            });
        })
            .then(function (response) {
                upload.complete(response);
            })
            .catch(function (response) {
                upload.complete(response);
                alert(response.message);
            });
    };
};
