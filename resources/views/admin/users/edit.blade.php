<x-chief::page.template :title="$user->fullname" container="md">
    <x-slot name="header">
        <x-chief::page.header
            :breadcrumbs="[
                ['label' => 'Admins', 'url' => route('chief.back.users.index'), 'icon' => 'user'],
                $user->fullname
            ]"
        >
            <x-slot name="actions" class="items-center">
                @php
                    $badge = $user->present()->getStateBadge();
                @endphp

                <x-chief::badge variant="{{ $badge['variant'] }}" size="sm">
                    {{ $badge['label'] }}
                </x-chief::badge>

                @if ($user->isEnabled())
                    <x-chief::button form="updateForm" type="submit" variant="blue">
                        <span>Bewaar</span>
                    </x-chief::button>
                @endif

                <x-chief::button x-data x-on:click="$dispatch('open-dialog', { 'id': 'user-edit-options' });">
                    <x-chief::icon.more-vertical-circle />
                </x-chief::button>

                <x-chief::dialog.dropdown id="user-edit-options">
                    <x-chief::dialog.dropdown.item
                        href="{{ route('chief.back.invites.resend', $user->id) }}"
                        title="Stuur nieuwe uitnodiging"
                    >
                        <x-chief::icon.mail-add />
                        <x-chief::dialog.dropdown.item.content label="Stuur nieuwe uitnodiging" />
                    </x-chief::dialog.dropdown.item>

                    @if ($user->isEnabled())
                        <x-chief::dialog.dropdown.item type="submit" form="disableUserForm" variant="red">
                            <x-chief::icon.square-lock />
                            <x-chief::dialog.dropdown.item.content label="{{ ucfirst($user->firstname) }} blokkeren" />
                        </x-chief::dialog.dropdown.item>
                    @else
                        <x-chief::dialog.dropdown.item type="submit" form="enableUserForm" variant="green">
                            <x-chief::icon.square-unlock />
                            <x-chief::dialog.dropdown.item.content
                                label="{{ ucfirst($user->firstname) }} deblokkeren"
                            />
                        </x-chief::dialog.dropdown.item>
                    @endif
                </x-chief::dialog.dropdown>
            </x-slot>
        </x-chief::page.header>
    </x-slot>

    <x-chief::window>
        <form id="updateForm" action="{{ route('chief.back.users.update', $user->id) }}" method="POST">
            @csrf
            @method('put')

            @include('chief::admin.users._form')
        </form>
    </x-chief::window>

    @if ($user->isEnabled())
        <form id="disableUserForm" method="POST" action="{{ route('chief.back.users.disable', $user->id) }}">
            @csrf
        </form>
    @else
        <form id="enableUserForm" method="POST" action="{{ route('chief.back.users.enable', $user->id) }}">
            @csrf
        </form>
    @endif
</x-chief::page.template>
