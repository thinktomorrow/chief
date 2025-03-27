<x-chief::solo.template title="Er ging iets fout">
    <div class="space-y-6">
        <div class="prose prose-dark prose-spacing">
            <p>
                Onze developers werden op de hoogte gebracht en zullen uitzoeken wat er fout liep.
                <br />
                Indien je dringend hulp nodig hebt bij dit probleem, kan je ons best contacteren.
            </p>
        </div>

        <div class="space-y-3">
            <x-chief::button
                href="{{ route('chief.back.dashboard') }}"
                title="Naar het dashboard"
                size="lg"
                variant="blue"
                class="flex w-full justify-center"
            >
                Naar het dashboard
            </x-chief::button>

            <x-chief::button
                href="mailto:support@thinktomorrow.be"
                title="Contacteer ons"
                size="lg"
                class="flex w-full justify-center"
            >
                Contacteer ons
            </x-chief::button>
        </div>
    </div>
</x-chief::solo.template>
