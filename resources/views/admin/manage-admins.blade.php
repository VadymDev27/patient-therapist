<x-app-layout x-data="{ open: false, email: '', id: '', adding: false, new_email: '{{ old('email', '')}}', new_permissions: {{ json_encode(old('permissions', [])) }}, get permissionsSet() { return this.new_permissions.length === 0; } }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Users
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="">Welcome to the TOP DD RCT Admin Dashboard! You can manage users' permissions below. </div>
                    <div class="mb-4">Please note that you cannot edit your own permissions, or the permissions of the original administrator. You also cannot assign permissions to users to perform actions you do not have permission to perform.</div>
                    @if(session('status') === 'admin-successfully-updated')
                        <div class="mb-4 text-green-500">
                            Your changes have been successfully saved.
                        </div>
                    @endif
                    <x-auth.validation-errors class="mb-4" :errors="$errors" />

                    <div>

                    <table>
                        <tr>
                            <x-admin.table-cell class="font-semibold" stripe rowspan="2">Email address</x-admin.table-cell>
                            <x-admin.table-cell class="font-semibold text-center" stripe colspan="{{ count($permissions)}}">Permissions</x-admin.table-cell>
                            <x-admin.table-cell class="font-semibold text-center" stripe rowspan="2">Delete user</x-admin.table-cell>
                        </tr>
                        <tr>
                            @foreach($permissions as $code => $label)
                            <x-admin.table-cell class="text-center" stripe>{{ $label }} </x-admin.table-cell>
                            @endforeach
                        </tr>
                        <form method="POST" action="{{ route('admin.users.update') }}" id="update_form">
                        @csrf
                        @method('PUT')
                        @foreach ($admins as $admin)
                            <tr @class(['bg-blue-100' => $admin->is(Auth::user()),])>
                                <x-admin.table-cell>{{ $admin->email }}</x-admin.table-cell>
                                @foreach($permissions as $code => $label)
                                    <x-admin.table-cell class="text-center" x-data="{ options: {{ json_encode($admin->admin_permissions) }} }">
                                        <input type="checkbox"
                                            class="rounded disabled:opacity-50"
                                            name="{{ 'admins['.$admin->id . '][]'}}"
                                            value="{{ $code }}"
                                            x-model="options"
                                            {{ ( $admin->canBeEdited() && Auth::user()->canDo($code) ) ? '' : 'disabled' }}
                                        >
                                    </x-admin.table-cell>

                                @endforeach
                                <x-admin.table-cell class="text-center">
                                    <button
                                        type="button"
                                        class="text-sm border border-gray-500 shadow-sm p-1 rounded-lg hover:bg-gray-200"
                                        x-data="{ admin_email: '{{ $admin->email }}', admin_id: '{{ $admin->id }}' }"
                                        x-on:click="open=true;email=admin_email;id=admin_id"
                                        {{ $admin->canBeDeleted() ? '' : 'disabled' }}
                                    >
                                        Delete
                                    </button>
                                </x-admin.table-cell>

                            </tr>
                        @endforeach
                        </form>

                        {{-- ADD NEW ADMINS --}}
                        @cando('invite-admin')
                        <form method="POST" action="{{ route('admin.users.store') }}" id="new_admin">
                            @csrf
                        <tr x-cloak x-show="adding" >
                            <x-admin.table-cell>
                                <input type="text" name="email" class="border border-gray-300 rounded-sm p-0 w-full h-full m-0" x-model="new_email"/>
                            </x-admin.table-cell>
                            @foreach($permissions as $code => $label)
                                <x-admin.table-cell class="text-center">
                                    <input type="checkbox"
                                        class="rounded disabled:opacity-50"
                                        name="permissions[]"
                                        value="{{ $code }}"
                                        x-model="new_permissions"
                                        {{ Auth::user()->canDo($code) ? '' : 'disabled'}}
                                    >
                                </x-admin.table-cell>
                            @endforeach

                            <x-admin.table-cell class="text-center">
                                <button  class="text-sm text-white bg-logo-blue shadow-sm py-1 px-3 rounded-lg hover:bg-gray-200 disabled:opcaity-50">Add</button>
                            </x-admin.table-cell>
                        </tr>
                        </form>
                        @endcando
                        {{-- END ADD NEW ADMINS --}}

                    </table>
                    <div class="flex flex-row justify-between" x-data="{
                        message: '+ Add new user',
                        toggle() {
                            adding = ! adding;
                            this.message = adding
                                    ? '- Cancel add new user'
                                    : '+ Add new user';
                            new_email = '';
                            new_permissions = [];
                        }
                    }">
                            @cando('invite-admin')
                            <button type="button" class="m-2 py-1 px-3 bg-gray-200 border border-gray-200 shadow-sm rounded-sm " x-on:click="toggle()"><span x-text="message"></span></button>
                            @else
                            <div></div>
                            @endcando
                            <x-button form="update_form" x-bind:disabled="adding">Update users</x-button>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal x-cloak x-show="open">
        Are you sure you want to delete user <span x-text="email"></span>?
        This action is irreversible, but you can always reinvite the user later if you change your mind.

        <x-slot name="buttons">
            <form method="POST" x-bind:action="'{{ route('admin.users.index') }}' + '/' + id">
                @csrf
                @method('DELETE')
            <x-admin.modal.go-button>
                Delete user
            </x-admin.modal.go-button>
            <x-admin.modal.cancel-button x-on:click="open=false"/>
            </form>
        </x-slot>
    </x-admin.modal>
</x-app-layout>
