<span {{
    $attributes
        ->merge(['class' => 'inline-flex items-start text-sm leading-5 gap-2 py-1.5 px-2 rounded-xl transition-all duration-75 ease-in-out'])
        ->merge(['class' => 'text-grey-700 bg-grey-100 hover:text-primary-500 hover:bg-primary-50 shadow-sm'])
        ->merge(['class' => '[&_svg]:shrink-0 [&_svg]:w-5 [&_svg]:h-5 [&_svg]:-mx-0.5 [&_svg]:transition-all [&_svg]:duration-75 [&_svg]:ease-in-out [&:hover_svg]:scale-110'])
}}>
    {{ $slot }}
</span>
