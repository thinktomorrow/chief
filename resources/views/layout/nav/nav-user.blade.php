@can('update-you')
    <a href="{{ route('chief.back.you.edit') }}" class="link link-black">
        <x-icon-label icon="icon-user" space="large">{{ ucfirst(chiefAdmin()->firstname) }}</x-icon-label>
    </a>
@endcan

<a class="link link-black" href="{{ route('chief.back.logout') }}">
    <x-icon-label icon="icon-logout" space="large">Logout</x-icon-label>
</a>
