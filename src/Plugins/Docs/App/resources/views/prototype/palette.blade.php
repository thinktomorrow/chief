{{-- Command-palette search over the (prototype) client-side index --}}
<div
    x-data="{
        open: false, q: '', active: 0,
        index: @js($searchIndex),
        get results() {
            const words = this.q.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').split(/\s+/).filter(w => w.length >= 2);
            if (! words.length) return [];
            return this.index.filter(g => {
                const hay = (g.title + ' ' + (g.description || '') + ' ' + g.text).toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
                return words.every(w => hay.includes(w));
            }).slice(0, 8).map(g => {
                const pos = g.text.toLowerCase().indexOf(words[0]);
                let snip = pos < 0 ? (g.description || '') : '…' + g.text.substr(Math.max(0, pos - 50), 150) + '…';
                snip = snip.replace(/[&<>]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[c]));
                words.forEach(w => snip = snip.replace(new RegExp('(' + w.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'ig'), '<mark>$1</mark>'));
                return { ...g, snip };
            });
        },
        go() { const r = this.results[this.active]; if (r) location.href = r.url; },
    }"
    x-on:pt-open-palette.window="open = true; $nextTick(() => $refs.input.focus())"
    x-on:keydown.window.meta.k.prevent="open = true; $nextTick(() => $refs.input.focus())"
    x-on:keydown.window.ctrl.k.prevent="open = true; $nextTick(() => $refs.input.focus())"
    x-on:keydown.escape.window="open = false"
>
    <template x-if="open">
        <div class="pt-palette-backdrop" x-on:click.self="open = false">
            <div class="pt-palette">
                <div class="pt-search" style="border: 0; border-bottom: 1px solid #e4e4e7; border-radius: 0; padding: 1rem 1.1rem">
                    <x-chief::icon.search class="text-grey-400 size-5 shrink-0" />
                    <input x-ref="input" x-model="q" x-on:input="active = 0" x-on:keydown.down.prevent="active = Math.min(active + 1, results.length - 1)" x-on:keydown.up.prevent="active = Math.max(active - 1, 0)" x-on:keydown.enter.prevent="go()" placeholder="Zoek in de handleiding…" style="font-size: 1rem">
                </div>
                <div class="pt-results max-h-[60vh] overflow-y-auto p-2">
                    <template x-for="(r, i) in results" :key="r.id">
                        <a :href="r.url" :class="{ 'is-active': i === active }" x-on:mouseenter="active = i">
                            <span class="pt-crumb" x-text="r.section"></span>
                            <strong x-text="r.title"></strong>
                            <p x-html="r.snip"></p>
                        </a>
                    </template>
                    <p x-show="q.length >= 2 && ! results.length" class="text-grey-500 p-4 text-center text-sm">Niets gevonden voor “<span x-text="q"></span>”</p>
                    <p x-show="q.length < 2" class="text-grey-400 p-4 text-center text-sm">Typ minstens 2 letters · ↑↓ om te kiezen · Enter om te openen</p>
                </div>
            </div>
        </div>
    </template>
</div>
