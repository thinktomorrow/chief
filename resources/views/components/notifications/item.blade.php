@props([
    "type" => "info",
    "duration" => 5000,
])

<div
    {{ $attributes->class(["flex origin-right items-center gap-6 rounded-lg border border-grey-100 bg-white px-6 py-4 shadow-lg"]) }}
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
            <x-chief::icon.information-circle class="size-6 bg-primary-50 text-primary-500" />
        </template>

        <template x-if="type == 'success'">
            <x-chief::icon.checkmark-circle class="size-6 bg-green-50 text-green-500" />
        </template>

        <template x-if="type == 'warning'">
            <x-chief::icon.alert-circle class="size-6 bg-orange-50 text-orange-500" />
        </template>

        <template x-if="type == 'error'">
            <x-chief::icon.alert-circle class="size-6 bg-red-50 text-red-500" />
        </template>
    </div>

    <div class="prose grow text-grey-500">
        {{ $slot }}
    </div>

    <button type="button" class="shrink-0" x-on:click="isOpen = false">
        <x-chief::icon.cancel class="size-5 text-grey-500" />
    </button>
</div>
