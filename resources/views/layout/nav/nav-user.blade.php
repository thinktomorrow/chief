@can('update-you')
    <a href="{{ route('chief.back.you.edit') }}" class="link link-black">
        <x-icon-label icon="icon-user" space="large">{{ ucfirst(chiefAdmin()->firstname) }}</x-icon-label>
    </a>
@endcan

<a class="link link-black" href="{{ route('chief.back.logout') }}">
    <x-icon-label icon="icon-logout" space="large">Logout</x-icon-label>
</a>

{{-- <dropdown>
    <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">
        <x-icon-label icon="icon-user" space="large">{{ chiefAdmin()->firstname }}</x-icon-label>
    </span>

    <div v-cloak class="dropdown-content">
        @can('update-you')
            <a class="dropdown-link" href="{{ route('chief.back.you.edit') }}">Wijzig profiel</a>
        @endcan

        <a class="dropdown-link" href="{{ route('chief.back.logout') }}">Log out</a>
    </div>
</dropdown> --}}
