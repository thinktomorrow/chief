<x-chief::solo.template title="Er ging iets fout">
    <div class="space-y-6">
        <div class="prose prose-spacing prose-dark">
            <p>
                Onze developers werden op de hoogte gebracht en zullen uitzoeken wat er fout liep.<br>
                Indien je dringend hulp nodig hebt bij dit probleem, kan je ons best contacteren.
            </p>
        </div>

        <div class="space-y-3">
            <a
                href="{{ route('chief.back.dashboard') }}"
                title="Naar het dashboard"
                class="flex justify-center w-full btn btn-primary"
            > Naar het dashboard </a>

            <a
                href="mailto:support@thinktomorrow.be"
                title="Contacteer ons"
                class="flex justify-center w-full btn btn-grey"
            > Contacteer ons </a>
        </div>
    </div>
</x-chief::solo.template>
