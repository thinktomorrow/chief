<tr data-table-row {{ $attributes->class("[&>*:first-child]:pl-8 [&>*:last-child]:pr-8") }}>
{{-- <tr data-table-row {{ $attributes }}> --}}
    {{ $slot }}
</tr>
