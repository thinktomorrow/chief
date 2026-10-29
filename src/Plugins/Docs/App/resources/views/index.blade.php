<x-chief::page.template title="Handleiding" container="full">
    <x-slot name="nav" class="lg:w-80">
        <x-chief-docs::sidebar :tree="$tree" />
    </x-slot>

    <article class="border-grey-100 grow bg-white lg:border-l">
        <div class="mx-auto max-w-3xl space-y-10 px-6 py-10 lg:px-12 lg:py-16">
            <h1 class="h1 h1-dark text-pretty">Handleiding</h1>

            @include ('chief-docs::tree', ['nodes' => $tree->roots()])
        </div>
    </article>
</x-chief::page.template>
