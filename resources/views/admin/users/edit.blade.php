@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar admins', route('chief.back.users.index'));
@endphp

<x-chief::page.template :title="$user->fullname">
    <x-slot name="hero">
        <x-chief::page.hero :title="$user->fullname" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <div class="flex items-center gap-4">
                {!! $user->present()->enabledAsLabel() !!}

                @if ($user->isEnabled())
                    <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
                @endif

                <x-chief::button x-data x-on-click="$dispatch('open-dialog', { 'id': 'user-edit-options' });">
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
            </div>
        </x-chief::page.hero>
    </x-slot>

    @if ($user->isEnabled())
        <form id="disableUserForm" method="POST" action="{{ route('chief.back.users.disable', $user->id) }}">
            @csrf
        </form>
    @else
        <form id="enableUserForm" method="POST" action="{{ route('chief.back.users.enable', $user->id) }}">
            @csrf
        </form>
    @endif

    <x-chief::page.grid class="max-w-3xl">
        <form id="updateForm" action="{{ route('chief.back.users.update', $user->id) }}" method="POST" class="card">
            @csrf
            @method('put')

            @include('chief::admin.users._form')
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
