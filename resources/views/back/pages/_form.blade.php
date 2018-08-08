<tabs>
    <tab name="Pagina" id="builder">
        <section class="formgroup stack">
            <h2>Pagina inhoud</h2>
            <page-builder
                    :locales='@json($page->availableLocales())'
                    :default-sections='@json($sections)'
                    :modules='@json($relations)'>
            </page-builder>
        </section>

        <div class="stack clearfix">
            <a href="#inhoud" class="btn btn-o-primary right">volgende</a>
        </div>
    </tab>
    <tab name="Inhoud" id="inhoud">
        <section class="row formgroup stack gutter-l">
            <div class="column-4">
                <h2 class="formgroup-label">{{ $page->collectionDetails()->singular }} titel</h2>
            </div>
            <div class="formgroup-input column-8">

                @if(count($page->availableLocales()) > 1)
                    <tabs v-cloak>
                        @foreach($page->availableLocales() as $locale)
                            <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.title')}">
                                @include('chief::back.pages._partials.title-form')
                            </tab>
                        @endforeach
                    </tabs>
                @else
                    @foreach($page->availableLocales() as $locale)
                        @include('chief::back.pages._partials.title-form')
                    @endforeach
                @endif
            </div>
        </section>

        @if(count($page->translatableFields()) > 0)
            <section class="row formgroup stack gutter-l">
                <div class="column-4">
                    <h2 class="formgroup-label">Inhoud</h2>
                    <p>Deze titel en inhoud wordt weergegeven als je dit {{ $page->collectionDetails()->singular }} koppelt als module aan een pagina </p>
                </div>
                <div class="formgroup-input column-8">
                    @include('chief::back._elements.translatable_fieldgroups', [
                        'model' => $page,
                    ])
                </div>
            </section>
        @endif

        <div class="stack clearfix">
            <a href="#builder" class="btn btn-o-primary left">Vorige</a>
            <a href="#modules" class="btn btn-o-primary right">volgende</a>        </div>
    </tab>

    <tab name="Eigen modules" id="modules">

        @include('chief::back.pages._partials.modules')

        @foreach($page->mediaFields() as $media)

            <?php

            $viewPath = (isset($media['is_document']) && $media['is_document'])
                ? 'chief::back._elements.mediagroup-documents'
                : 'chief::back._elements.mediagroup-images';

            ?>

            @include($viewPath, [
                'group' => $media['type'],
                'files' => $images[$media['type']],
                'label' => $media['label'],
                'description' => $media['description'],
            ])
        @endforeach

        <div class="stack clearfix">
            <a href="#builder" class="btn btn-o-primary left">Vorige</a>
            <a href="#seo" class="btn btn-o-primary right">volgende</a>
        </div>
    </tab>

<tab name="Seo">
    <section class="row formgroup stack gutter-l">
        <div class="column-4">
            <h2 class="formgroup-label">Zoekmachines</h2>
            <p class="caption">Titel en omschrijving van het pagina zoals het in search engines (o.a. google) wordt weergegeven.</p>
        </div>
        <div class="formgroup-input column-7">
            @if(count($page->availableLocales()) > 1)
                <tabs v-cloak>
                    @foreach($page->availableLocales() as $locale)
                        <tab name="{{ $locale }}" :options="{ hasErrors: errors.has('trans.{{ $locale }}.seo_title')}">
                            @include('chief::back.pages._partials.seo-form')
                        </tab>
                    @endforeach
                </tabs>
            @else
                @foreach($page->availableLocales() as $locale)
                    @include('chief::back.pages._partials.seo-form')
                @endforeach
            @endif
        </div>
    </section>

    <div class="stack clearfix">
        <a href="#modules" class="btn btn-o-primary left">Vorige</a>
        <button type="submit" class="btn btn-primary right">Wijzigingen opslaan</button>
    </div>
</tab>
</tabs>


@push('custom-scripts')
<script>
Vue.component('chief-permalink', {
    props: ['root', 'defaultPath'],
    data: function(){
        return {
            path: this.defaultPath || '',
            editMode: false,
        };
    },
    computed: {
        fullUrl: function(){
            return this.root + '/' + this.path;
        }
    },
    render: function(){
        return this.$scopedSlots.default({
            data: this.$data,
            fullUrl: this.fullUrl
        });
    }
});
</script>
@endpush
