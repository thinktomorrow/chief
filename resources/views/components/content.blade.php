<section id="content" class="container relative pb-64">
    <div v-cloak class="v-loading inset-xl text-center" style="position: absolute; top: 0;left: 0;z-index: 99;width: 100%;height: 100%;">loading...</div>

    <div v-cloak>
        @include('chief::layout._partials.notifications')
        {!! $slot !!}
    </div>
</section>
