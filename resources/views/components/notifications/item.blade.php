@props([
    'type' => 'info',
    'duration' => 5000,
])

<div
    {{ $attributes->class(['flex items-center px-6 py-4 gap-6 origin-right bg-white border rounded-lg shadow-lg border-grey-100 pop']) }}
    @if(!$attributes->has('x-data'))
        x-data="{ isOpen: true, type: '{{ $type }}', duration: '{{ $duration }}' }"
    @endif
    x-init="() => {
        setTimeout(() => { isOpen = false }, duration);

        $watch('isOpen', value => {
            value ?  $dispatch('notification-opened') : $dispatch('notification-closed');
        });

        window.addEventListener('close-all-notifications', () => { isOpen = false });
        window.addEventListener('open-all-notifications', () => { isOpen = true });
    }"
    x-show="isOpen"
>
    <div class="rounded-full shrink-0">
        <template x-if="type == 'info'">
            <svg class="w-6 h-6 text-primary-500 bg-primary-50"><use xlink:href="#icon-information-circle"></use></svg>
        </template>

        <template x-if="type == 'success'">
            <svg class="w-6 h-6 text-green-500 bg-green-50"><use xlink:href="#icon-check-circle"></use></svg>
        </template>

        <template x-if="type == 'warning'">
            <svg class="w-6 h-6 text-orange-500 bg-orange-50"><use xlink:href="#icon-exclamation-triangle"></use></svg>
        </template>

        <template x-if="type == 'error'">
            <svg class="w-6 h-6 text-red-500 bg-red-50"><use xlink:href="#icon-exclamation-circle"></use></svg>
        </template>
    </div>

    <div class="prose grow text-grey-500">
        {{ $slot }}
    </div>

    <button type="button" class="shrink-0" x-on:click="isOpen = false">
        <svg class="w-5 h-5 text-grey-500"><use xlink:href="#icon-x-mark"></use></svg>
    </button>
</div>
