@php
    $breadcrumb = new \Thinktomorrow\Chief\Admin\Nav\BreadCrumb('Terug naar admins', route('chief.back.users.index'));
@endphp

<x-chief::page.template :title="$user->fullname">
    <x-slot name="hero">
        <x-chief::page.hero :title="$user->fullname" :breadcrumbs="[$breadcrumb]" class="max-w-3xl">
            <div class="flex items-center gap-4">
                {!! $user->present()->enabledAsLabel() !!}

                @if($user->isEnabled())
                    <button form="updateForm" type="submit" class="btn btn-primary">Opslaan</button>
                @endif

                <button type="button" id="user-edit-options">
                    <x-chief::button>
                        <svg class="w-5 h-5"><use xlink:href="#icon-ellipsis-vertical"/></svg>
                    </x-chief::button>
                </button>

                <x-chief::dropdown trigger="#user-edit-options">
                    <a href="{{ route('chief.back.invites.resend', $user->id) }}" title="Stuur nieuwe uitnodiging">
                        <x-chief::dropdown.item>
                            Stuur nieuwe uitnodiging
                        </x-chief::dropdown.item>
                    </a>

                    @if($user->isEnabled())
                        <button type="submit" form="disableUserForm">
                            <x-chief::dropdown.item>
                                {{ ucfirst($user->firstname) }} blokkeren
                            </x-chief::dropdown.item>
                        </button>
                    @else
                        <button type="submit" form="enableUserForm">
                            <x-chief::dropdown.item>
                                {{ ucfirst($user->firstname) }} deblokkeren
                            </x-chief::dropdown.item>
                        </button>
                    @endif
                </x-chief::dropdown>
            </div>
        </x-chief::page.hero>
    </x-slot>

    @if($user->isEnabled())
        <form id="disableUserForm" method="POST" action="{{ route('chief.back.users.disable', $user->id) }}">
            @csrf
        </form>
    @else
        <form id="enableUserForm" method="POST" action="{{ route('chief.back.users.enable', $user->id) }}">
            @csrf
        </form>
    @endif

    <x-chief::page.grid class="max-w-3xl">
        <form id="updateForm" action="{{ route('chief.back.users.update',$user->id) }}" method="POST" class="card">
            @csrf
            @method('put')

            @include('chief::admin.users._form')
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
