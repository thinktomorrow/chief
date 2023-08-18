<span {{
    $attributes
        ->merge(['class' => 'inline-flex items-start text-sm leading-5 gap-2 py-1.5 px-2 rounded-xl transition-all duration-75 ease-in-out'])
        ->merge(['class' => 'text-grey-700 bg-grey-100 hover:text-primary-500 hover:bg-primary-50 shadow-sm'])
        ->merge(['class' => '[&>svg]:shrink-0 [&>svg]:w-5 [&>svg]:h-5 [&>svg]:-mx-0.5 [&>svg]:transition-all [&>svg]:duration-75 [&>svg]:ease-in-out [&:hover>svg]:scale-110'])
}}>
    {{ $slot }}
</span>
