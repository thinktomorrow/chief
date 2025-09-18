@props([
    'header' => null,
    'footer' => null,
    'variant' => 'card',
])

<div
    {{
        $attributes->class([
            'divide-grey-100 ring-grey-100 divide-y rounded-xl ring-1',
            'shadow-grey-500/10 rounded-xl bg-white shadow-md' => $variant === 'card',
            '' => $variant === 'transparent',
        ])
    }}
>
    @if ($header)
        {{ $header }}
    @endif

    <div
        x-data="{
            isScrollable: false,
            isScrolledToLeft: false,
            isScrolledToRight: false,
            init() {
                this.isScrollable = this.$el.scrollWidth > this.$el.clientWidth
                $nextTick(() => {
                    this.updateScrollState()
                })
                this.$el.addEventListener('scroll', () => this.updateScrollState())
                window.addEventListener('resize', () => this.updateScrollState())
            },
            updateScrollState() {
                this.isScrollable = this.$el.scrollWidth > this.$el.clientWidth
                this.isScrolledToLeft = this.$el.scrollLeft <= 0
                this.isScrolledToRight =
                    this.$el.scrollLeft >= this.$el.scrollWidth - this.$el.clientWidth
            },
        }"
        class="scrollbar:hidden overflow-x-auto whitespace-nowrap"
    >
        <table class="divide-grey-100 min-w-full table-fixed divide-y">
            {{ $slot }}
        </table>
    </div>

    @if ($footer)
        {{ $footer }}
    @endif
</div>
