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

                <options-dropdown>
                    <div v-cloak class="dropdown-content">
                        <a class="dropdown-link" href="{{ route('chief.back.invites.resend', $user->id) }}">Stuur nieuwe uitnodiging</a>

                        @if($user->isEnabled())
                            <button form="disableUserForm" form="disableUserForm" type="submit" class="text-left dropdown-link">
                                {{ ucfirst($user->firstname) }} blokkeren
                            </button>
                        @else
                            <button form="enableUserForm" form="enableUserForm" type="submit" class="text-left dropdown-link">
                                {{ ucfirst($user->firstname) }} deblokkeren
                            </button>
                        @endif
                    </div>
                </options-dropdown>
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

            <div class="space-y-6">
                @include('chief::admin.users._form')
            </div>
        </form>
    </x-chief::page.grid>
</x-chief::page.template>
