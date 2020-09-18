<div class="mt-2 opacity-50 text-sm bg-grey-100 px-3 py-2 rounded-b border border-grey-300 border-t-0" style="font-family: monospace; margin-top: -1px;">
    <p><span id="{{ $key }}-wordcount">0</span>/{{ $field->getCharacterCount() }} karakters</p>
</div>

@push('custom-scripts-after-vue')
    <script>

        function characterCount(id, max) {

            let formField = document.getElementById(id),
                characterCountEl = document.getElementById(id + "-wordcount");

            formField.addEventListener("input", function(){
                var currentLength = this.value.length;

                if(currentLength >= max) {
                    characterCountEl.classList.add('text-error');
                } else {
                    characterCountEl.classList.remove('text-error');
                }

                characterCountEl.innerHTML = currentLength;
            });

            document.addEventListener('DOMContentLoaded', function(){
                characterCountEl.innerHTML = formField.value.length;
            });
        }

        new characterCount('{{ $key }}', {{ $field->getCharacterCount() }})

    </script>
@endpush
