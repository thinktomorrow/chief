<dropdown>
    <span class="link link-black" slot="trigger" slot-scope="{ toggle, isActive }" @click="toggle">
        <x-link-label type="user" space="large">{{ chiefAdmin()->firstname }}</x-link-label>
    </span>

    <div v-cloak class="dropdown-content">
        @can('update-you')
            <a class="dropdown-link" href="{{ route('chief.back.you.edit') }}">Wijzig profiel</a>
        @endcan

        <a class="dropdown-link" href="{{ route('chief.back.password.edit') }}">Wijzig wachtwoord</a>
        <a class="dropdown-link" href="{{ route('chief.back.logout') }}">Log out</a>
    </div>
</dropdown>
