<x-chief::page.template title="Documentatie" container="lg">
    <ul class="space-y-2">
        @foreach($files as $file)
            <li>
                <a href="{{ url('/admin/docs/'.$file) }}"
                   class="text-sky-600 hover:underline">
                    {{ ucfirst($file) }}
                </a>
            </li>
        @endforeach
    </ul>
</x-chief::page.template>
