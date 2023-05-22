<?php $componentId = \Illuminate\Support\Str::random(); ?>

<div id="{{ $componentId }}" x-cloak x-data="{open:@entangle('isOpen')}" x-show="open" class="fixed inset-0 flex items-center justify-center z-[100]">

    @if($isOpen)
        <div class="absolute inset-0 bg-black/50"></div>

        <div class="relative p-12 bg-white rounded-xl">

            <button class="btn btn-primary-outline" type="button" x-on:click="open = false">X</button>

            <!-- form prevents enter key in fields in this modal context to trigger submits of other form on the page -->
            <form>
                <div class="flex items-start gap-12 w-[56rem]">
                    <div class="space-y-6 shrink-0">
                        <button type="button" x-on:click="open = false">annuleren</button>
                        <button wire:click.prevent="submit" type="submit" class="btn btn-primary">Verwijderen</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
