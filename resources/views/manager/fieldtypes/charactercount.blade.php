<div class="px-5 pt-3 pb-2 -mt-1 border rounded-b-md border-grey-100 bg-grey-50" style="font-size: 12px;">
    <span class="font-mono text-sm leading-4 text-grey-500">
        <span id="{{ $key }}-wordcount">0</span>
        / {{ $field->getCharacterCount() }} karakters
    </span>
</div>

@push('custom-scripts-after-vue')
    <script>
        function characterCount(id, max) {
            var formField = document.getElementById(id);
            var characterCountEl = document.getElementById(id + "-wordcount");

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

        characterCount('{{ $key }}', {{ $field->getCharacterCount() }})
    </script>
@endpush
