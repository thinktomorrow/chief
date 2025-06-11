<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,100..900;1,100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet"
/>

<style>
    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-regular.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-regular.woff2') }}') format('woff2');
        font-weight: 400;
        font-style: normal;
    }

    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-oblique.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-oblique.woff2') }}') format('woff2');
        font-weight: 400;
        font-style: oblique;
    }

    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-medium.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-medium.woff2') }}') format('woff2');
        font-weight: 500;
        font-style: normal;
    }

    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-mediumoblique.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-mediumoblique.woff2') }}') format('woff2');
        font-weight: 500;
        font-style: oblique;
    }

    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-bold.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-bold.woff2') }}') format('woff2');
        font-weight: 700;
        font-style: normal;
    }

    @font-face {
        font-family: 'Ronzino';
        src:
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-boldoblique.woff') }}') format('woff'),
            url('{{ Vite::buildAsset('resources/assets/fonts/ronzino/ronzino-boldoblique.woff2') }}') format('woff2');
        font-weight: 700;
        font-style: oblique;
    }
</style>
