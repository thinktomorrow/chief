{{-- If you want to set a specific width to a table data element (cel), don't set it directly on this element.
Use a nested div inside this component with the expected width instead. --}}
<td {{ $attributes->merge(['class' => 'py-3 px-2 border-b border-grey-200 whitespace-nowrap']) }}>
    {{ $slot }}
</td>
