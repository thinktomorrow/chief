<div class="mt-2 opacity-50 text-sm bg-grey-100 px-3 py-2 rounded-b border border-grey-300 border-t-0" style="font-family: monospace; margin-top: -1px;">
    <p><span id="{{ $key }}-wordcount">0</span>/160 karakters</p>
</div>

@push('custom-scripts-after-vue')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.getElementById("{{$key}}-wordcount").innerHTML = document.getElementById('{{ $key }}').value.length;
        });

        document.getElementById('{{ $key }}').addEventListener("input", function(){
            var currentLength = this.value.length;

            document.getElementById("{{$key}}-wordcount").innerHTML = currentLength;
        });
    </script>
@endpush
