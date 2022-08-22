<div id="jsChiefToast"></div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        try {
            const toast = document.getElementById('jsChiefToast');

            fetch("{{ route('chief.toast.get') }}?path={{ request()->path() }}&locale={{ app()->getLocale() }}&preview_mode={{ \Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode::fromRequest()->check() }}")
                .then((response) => response.json())
                .then((data) => {
                    toast.innerHTML = data.data;
                    listenForClose();
                })
                .catch((error) => {
                    console.error(error);
                });

            function listenForClose() {
                const toastClose = toast.querySelector('[data-admin-toast-close]');

                if(toastClose) {
                    toastClose.addEventListener('click', function() {
                        toast.style.display = "none";
                    });
                }
            }
        } catch(error) {
            console.log(error);
        }
    })
</script>
