@props([
    "type" => "info",
    "duration" => 5000,
])

<div
    {{ $attributes->class(["pop flex origin-right items-center gap-6 rounded-lg border border-grey-100 bg-white px-6 py-4 shadow-lg"]) }}
    @if (! $attributes->has("x-data"))
        x-data="{
            isOpen: true,
            type: '{{ $type }}',
            duration: '{{ $duration }}',
        }"
    @endif
    x-init="
        () => {
            setTimeout(() => {
                isOpen = false
            }, duration)

            $watch('isOpen', (value) => {
                value
                    ? $dispatch('notification-opened')
                    : $dispatch('notification-closed')
            })

            window.addEventListener('close-all-notifications', () => {
                isOpen = false
            })
            window.addEventListener('open-all-notifications', () => {
                isOpen = true
            })
        }
    "
    x-show="isOpen"
>
    <div class="shrink-0 rounded-full">
        <template x-if="type == 'info'">
            <svg class="h-6 w-6 bg-primary-50 text-primary-500"><use xlink:href="#icon-information-circle"></use></svg>
        </template>

        <template x-if="type == 'success'">
            <svg class="h-6 w-6 bg-green-50 text-green-500"><use xlink:href="#icon-check-circle"></use></svg>
        </template>

        <template x-if="type == 'warning'">
            <svg class="h-6 w-6 bg-orange-50 text-orange-500"><use xlink:href="#icon-exclamation-triangle"></use></svg>
        </template>

        <template x-if="type == 'error'">
            <svg class="h-6 w-6 bg-red-50 text-red-500"><use xlink:href="#icon-exclamation-circle"></use></svg>
        </template>
    </div>

    <div class="prose grow text-grey-500">
        {{ $slot }}
    </div>

    <button type="button" class="shrink-0" x-on:click="isOpen = false">
        <svg class="h-5 w-5 text-grey-500"><use xlink:href="#icon-x-mark"></use></svg>
    </button>
</div>
