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

                <x-chief::badge variant="{{ $badge['variant'] }}" size="sm" class="mt-1.25">
                    {{ $badge['label'] }}
                </x-chief::badge>

                <x-chief::button form="updateForm" type="submit" variant="blue">
                    <span>Bewaar</span>
                </x-chief::button>

                <x-chief::button x-data x-on:click="$dispatch('open-dialog', { id: 'user-edit-options' })">
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

                    @if (chiefAdmin()->can('delete-user') && chiefAdmin()->id !== $user->id)
                        <x-chief::dialog.dropdown.item
                            variant="red"
                            x-on:click="$dispatch('open-dialog', { id: 'delete-user-confirmation' })"
                        >
                            <x-chief::icon.delete />
                            <x-chief::dialog.dropdown.item.content
                                label="{{ ucfirst($user->firstname) }} verwijderen"
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

    @if (chiefAdmin()->can('delete-user') && chiefAdmin()->id !== $user->id)
        <x-chief::dialog.modal id="delete-user-confirmation" title="Gebruiker definitief verwijderen?" size="xs">
            <div class="prose prose-dark prose-spacing">
                <p>
                    De account van {{ $user->fullname }} wordt definitief verwijderd. Auditlogs en door deze
                    gebruiker verstuurde uitnodigingen blijven bewaard.
                </p>
            </div>

            <x-slot name="footer">
                <x-chief::dialog.modal.footer>
                    <x-chief::button type="button" variant="grey" x-on:click="close()">Annuleer</x-chief::button>
                    <x-chief::button type="submit" form="deleteUserForm" variant="red">Verwijder gebruiker
                    </x-chief::button>
                </x-chief::dialog.modal.footer>
            </x-slot>
        </x-chief::dialog.modal>
        <form id="deleteUserForm" method="POST" action="{{ route('chief.back.users.destroy', $user->id) }}">
            @csrf
            @method('delete')
        </form>
    @endif
</x-chief::page.template>
