{{-- The specific height value is necessary in order for the sticky table headers to work.
This because of an issue combining sticky element within a container with non-default overflow values --}}
<div class="overflow-x-scroll rounded-lg whitespace-nowrap h-[80vh]">
    <table class="min-w-full border-separate rounded-xl border-spacing-0">
        <thead>
            <x-chief::table.row>
                {{ $header }}
            </x-chief::table.row>
        </thead>

        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>
