@can('update-you')
    <a href="{{ route('chief.back.you.edit') }}" class="link link-black">
        <x-chief-icon-label icon="icon-user" space="large">{{ ucfirst(chiefAdmin()->firstname) }}</x-chief-icon-label>
    </a>
@endcan

<a class="link link-black" href="{{ route('chief.back.logout') }}">
    <x-chief-icon-label icon="icon-logout" space="large">Logout</x-chief-icon-label>
</a>
