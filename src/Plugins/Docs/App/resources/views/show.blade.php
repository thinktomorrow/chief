<x-chief::page.template title="Documentatie" container="lg">
    <a href="{{ url('/admin/docs') }}" class="text-sky-600 hover:underline">&larr; Terug</a>

    <div class="prose prose-sky max-w-none mt-6">
        {!! $html !!}
    </div>
</x-chief::page.template>
