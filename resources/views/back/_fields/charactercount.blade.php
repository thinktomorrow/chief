<div><span id="{{$key}}-wordcount">0</span> karakters</div>

@push('custom-scripts-after-vue')
    <script>
        document.getElementById('{{ $key }}').addEventListener("input", function(){
            var currentLength = this.value.length;

            document.getElementById("{{$key}}-wordcount").innerHTML = currentLength;
        });
    </script>
@endpush
