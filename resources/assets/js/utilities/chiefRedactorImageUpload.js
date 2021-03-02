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
        return new Promise((resolve, reject) => {
            const token = document.head.querySelector('meta[name="csrf-token"]');

            function fileToDataURL(file) {
                const reader = new FileReader();

                return new Promise((resolveReaderPromise) => {
                    reader.onload = function () {
                        resolveReaderPromise({
                            data: reader.result,
                            filename: file.name,
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            function readAllAsDataURL(inputFiles) {
                return Promise.all(Array.from(inputFiles).map(fileToDataURL));
            }

            readAllAsDataURL(files).then((payload) => {
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
                        if (typeof response.data === 'string' && response.data.includes('POST Content-Length')) {
                            reject({
                                error: true,
                                message: 'De afbeelding is te groot en is niet opgeladen.',
                            });
                        }

                        resolve(response.data);
                    })
                    .catch((error) => {
                        console.error(error);
                        reject(error);
                    });
            });
        })
            .then((response) => {
                upload.complete(response);
            })
            .catch((response) => {
                upload.complete(response);
                alert(response.message);
            });
    };
};
