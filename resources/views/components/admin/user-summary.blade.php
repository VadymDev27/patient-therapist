@props(['user'])

<x-detail-box>
    <x-slot name="icon">
        <div class="hidden lg:flex flex-row gap-x-4 items-center text-center lg:items-baseline lg:flex-col">
            <button x-on:click="open=true,id='{{$user->id}}',email='{{$user->email}}'">
            <div class="text-center w-full">
                <svg class="block m-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 6v18h18v-18h-18zm5 14c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4-18v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712z"/></svg>
            </div>
            <div class="font-semibold text-sm">Delete</div>
            </button>
        </div>

    </x-slot>
    <x-detail-box.field label="ID">
        {{ $user->id }}
    </x-detail-box.field>
    <x-detail-box.field label="Pair ID">
        {{ $user->pair_id }}
    </x-detail-box.field>
    <x-detail-box.field label="Email">
        {{ $user->email }}
    </x-detail-box.field>
    <x-detail-box.field label="Role">
        {{ $user->role }}
    </x-detail-box.field>
    <x-detail-box.field label="Week">
        {{ $user->week }}
    </x-detail-box.field>


    <x-slot name="buttons">
        <form method="POST" action="{{ route('test-users.login',['user' => $user->id])}}" class="w-full">
            @csrf
            <button class="text-sm text-white bg-logo-blue shadow-sm py-1 px-3 rounded-lg hover:bg-gray-200 disabled:opacity-50 w-full">Log in as user</button>
        </form>
        <form method="GET" action="{{ route('test-users.edit', ['user' => $user->id]) }}" class="w-full">
        <button
            class="text-sm border border-gray-500 shadow-sm p-1 rounded-lg hover:bg-gray-200 w-full"
        >
            See details/edit
        </button>
        </form>
        <button x-on:click="open=true,id='{{$user->id}}',email='{{$user->email}}'"
            class="lg:hidden text-sm border border-gray-500 shadow-sm p-1 rounded-lg hover:bg-gray-200 w-full"
        >
            Delete user
        </button>
    </x-slot>
</x-detail-box>
