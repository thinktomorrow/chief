@php
    $firstGuide = $tree->readingOrder()[0] ?? null;
@endphp

<x-chief::page.template title="Handleiding" container="full">
    <x-slot name="nav" class="lg:w-80">
        <x-chief-docs::sidebar :tree="$tree" />
    </x-slot>

    <article class="border-grey-100 grow bg-white lg:border-l">
        <div class="mx-auto max-w-4xl space-y-4 px-6 py-10 lg:px-12 lg:py-16">
            <p class="text-primary-500 text-sm/6 font-medium">Handleiding</p>

            <h1 class="h1 h1-dark text-pretty">Hoe werkt Chief?</h1>

            <p class="body text-grey-500 text-lg">Alles wat je moet weten om je website te beheren: van pagina's opmaken tot menu's en instellingen.</p>

            @if ($firstGuide)
                <div class="flex flex-wrap gap-2 pt-4">
                    <x-chief::button variant="blue" :href="route('chief.docs.show', $firstGuide->id)">
                        Aan de slag
                    </x-chief::button>

                    <x-chief::button
                        type="button"
                        variant="outline-white"
                        x-data
                        x-on:click="$dispatch('open-dialog', { id: 'guide-search' })"
                    >
                        <x-chief::icon.search />
                        Zoeken
                    </x-chief::button>
                </div>

                @foreach ($tree->groups() as $group)
                    @php
                        $guides = $tree->guidesAmong($group->nodes);
                    @endphp

                    @if ($guides)
                        <section class="space-y-6 pt-12">
                            <h2 class="h3 h3-dark">{{ $group->title }}</h2>

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($guides as $guide)
                                    <x-chief-docs::guide-card
                                        :guide="$guide"
                                        :title="$guide === $group->section ? 'Inleiding' : null"
                                    />
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            @else
                <x-chief::empty-state title="Er is nog geen handleiding" class="pt-12">
                    Vraag je beheerder of ontwikkelaar om de eerste handleiding toe te voegen.
                </x-chief::empty-state>
            @endif
        </div>

        <livewire:chief-docs::guide-search />
    </article>
</x-chief::page.template>
