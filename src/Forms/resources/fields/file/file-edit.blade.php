<div class="fixed inset-0 flex items-center justify-center z-[100]">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative p-12 bg-white rounded-xl">
        <div class="flex items-start gap-12 w-[56rem]">
            <div class="space-y-6 shrink-0">
                <div class="overflow-hidden w-80 h-80 bg-grey-100 rounded-xl">
                    <img
                        src="https://i.picsum.photos/id/371/800/800.jpg?hmac=PD4fDPICaFVTJln3r1f8iwuUgASHubIW6XRIuJtzyEU"
                        alt="..."
                        class="object-contain w-full h-full"
                    >
                </div>

                <div class="flex flex-wrap gap-2">
                    <x-chief-icon-button color="grey">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><line x1="64" y1="64" x2="24" y2="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><polyline points="64 24 64 192 232 192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><polyline points="192 160 192 64 96 64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><line x1="192" y1="232" x2="192" y2="192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line></svg>
                    </x-chief-icon-button>

                    <x-chief-icon-button color="grey">
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><polyline points="176.2 99.7 224.2 99.7 224.2 51.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M65.8,65.8a87.9,87.9,0,0,1,124.4,0l34,33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><polyline points="79.8 156.3 31.8 156.3 31.8 204.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><path d="M190.2,190.2a87.9,87.9,0,0,1-124.4,0l-34-33.9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                    </x-chief-icon-button>

                    <x-chief-icon-button icon="icon-download" color="grey"/>
                </div>

                <div class="space-y-0.5 text-grey-500">
                    <div class="flex justify-between">
                        <span>Afmetingen</span>
                        <span>800x533</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Bestandsgrootte</span>
                        <span>604 KB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Bestandsextensie</span>
                        <span>PNG</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Datum toegevoegd</span>
                        <span>05/01/23 12:53</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Datum aangepast</span>
                        <span>11/01/23 07:10</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6 grow">
                <div>
                    <label for="..." class="inline-block mb-1 font-medium display-base display-dark">Bestandsnaam</label>

                    <input
                        type="text"
                        id="..."
                        name="..."
                        placeholder="Bestandsnaam"
                        class="w-full shadow-sm"
                    >
                </div>

                <div>
                    <label for="..." class="inline-block mb-1 font-medium display-base display-dark">Label</label>

                    <input
                        type="text"
                        id="..."
                        name="..."
                        placeholder="Label"
                        class="w-full shadow-sm"
                    >
                </div>

                <div>
                    <label for="..." class="inline-block mb-1 font-medium display-base display-dark">Alt</label>

                    <input
                        type="text"
                        id="..."
                        name="..."
                        placeholder="Alt"
                        class="w-full shadow-sm"
                    >
                </div>

                <div>
                    <label for="..." class="inline-block mb-1 font-medium display-base display-dark">Tags</label>

                    <div class="flex flex-wrap gap-2">
                        <span class="label label-grey">Achtergrond</span>
                        <span class="label label-grey">Unsplash</span>
                        <span class="label label-grey">Sustainability</span>
                    </div>
                </div>

                <div>
                    <label for="..." class="inline-block mb-1 font-medium display-base display-dark">Afgeschermd</label>

                    <x-chief-form::formgroup.checkbox for="..." :show-as-toggle="true">
                        <input type="checkbox" id="..." name="..." value="..." />
                    </x-chief-form::formgroup.checkbox>
                </div>

                <div>
                    <label for="..." class="items-baseline inline-block mb-1 font-medium display-base display-dark">
                        <span>Koppelingen</span>
                        <span class="label label-xs label-grey">3</span>
                    </label>

                    <div class="overflow-auto border divide-y rounded-lg border-grey-200 divide-grey-200 max-h-56">
                        <div class="flex items-center justify-between px-3 py-1.5">
                            <span class="text-black">Homepage</span>
                            <x-chief-icon-button icon="icon-external-link" color="grey"/>
                        </div>
                        <div class="flex items-center justify-between px-3 py-1.5">
                            <span class="text-black">Contacteer ons</span>
                            <x-chief-icon-button icon="icon-external-link" color="grey"/>
                        </div>
                        <div class="flex items-center justify-between px-3 py-1.5">
                            <span class="text-black">AI Generated Content</span>
                            <x-chief-icon-button icon="icon-external-link" color="grey"/>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-primary">Bestand opslaan</button>
                </div>
            </div>
        </div>
    </div>
</div>
