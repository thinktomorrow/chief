@props([
    'id' => null,
])

<label
    for="{{ $id }}"
    data-slot="control"
    x-data="{
        filenameString: null,
        niceBytes(x) {
            const units = ['bytes', 'KB', 'MB', 'GB']
            let l = 0
            let n = parseInt(x, 10) || 0
            while (n >= 1024 && ++l) {
                n /= 1024
            }
            return `${n.toFixed(n < 10 && l > 0 ? 1 : 0)} ${units[l]}`
        },
    }"
    class="border-grey-200 hover:border-grey-400 pointer-events-none relative flex items-center justify-center rounded-md border border-dashed bg-white p-4 shadow-xs"
>
    <span x-html="filenameString ? filenameString : '{{ $slot }}'" class="body body-dark text-center"></span>

    <input
        type="file"
        x-on:change="
            (e) => {
                filenameString = null
                Array.from(e.target.files).forEach((file) => {
                    filenameString += `${file.name} (${niceBytes(file.size)})<br>`
                })
            }
        "
        {{ $attributes->class('pointer-events-auto absolute inset-0 w-full cursor-pointer opacity-0') }}
    />
</label>
