<?php

$page = \Thinktomorrow\Chief\Pages\Page::ignoreCollection()->first();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="google" content="notranslate" />
    <meta http-equiv="Content-Language" content="nl-BE" />
    <meta name="author" content="Think Tomorrow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ cached_asset('/chief-assets/back/css/main.css','back') }}">
</head>
<body>

<form action="">
    <textarea data-editor name="content" id="" cols="30" rows="10"></textarea>
</form>

<script src="/chief-assets/back/js/redactor-test.js"></script>
<script src="/chief-assets/back/js/redactor-plugins/redactor-columns.js"></script>
<script src="/chief-assets/back/js/redactor-plugins/imagemanager.js"></script>
<script src="/chief-assets/back/js/redactor-plugins/alignment.js"></script>
<script>
    $R('[data-editor]', {
        plugins: ['redactorColumns', 'imagemanager', 'alignment'],
        buttons: ['html', 'format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
        formatting: ['p', 'h2', 'h3'],
        formattingAdd: {
            "rood": {
                title: 'Rode tekst',
                api: 'module.block.format',
                args: {
                    'tag': 'p',
                    'class': 'text-primary'
                }
            },
        },
        imageResizable: true,
        imagePosition: true,
        //        imageManagerJson: '/your-folder/images.json',
//        imageUpload: function(formData, files, event)
//        {
        // ... your process for uploading an image ...
        //  in the end, you must return JSON or a string with the image URL
        // return json;
        // or
        // return '/images/my-image.jpg';
//        }
        {{--callbacks: {--}}
                {{--upload: {--}}
                {{--beforeSend: function(xhr)--}}
                {{--{--}}
                {{--console.log(xhr);--}}
                {{--}--}}
                {{--},--}}
                {{--},--}}
        callbacks: {
            upload: {
                beforeSend: function(xhr)
                {
                    let token = document.head.querySelector('meta[name="csrf-token"]');

                    xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                }
            }
        },
        imageUpload: '{{ route('pages.media.upload', $page->id) }}',
        {{--imageUpload: function(data, files, e, upload)--}}
        {{--{--}}
        {{--const url = '{{ route('media.upload') }}';--}}

        {{--data.append('file', files[0]);--}}
        {{--data.append('model_type', '{{ addslashes(get_class($page)) }}');--}}
        {{--data.append('model_id', '{{ $page->id }}');--}}

        {{--return axios.post(url, data, {})--}}
        {{--.then(function(response){--}}
        {{--upload.complete(response);--}}
        {{--})--}}
        {{--.catch(function(error){--}}
        {{--upload.complete(error);--}}
        {{--});--}}
        {{--}--}}
    });
</script>
</body>
</html>