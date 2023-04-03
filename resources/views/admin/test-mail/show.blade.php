<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test User Mailbox
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex gap-x-2 items-center">
                        <a href="{{ route('users.notifications.index', ['user' => $user]) }}">
                             <x-button type="button">Return to inbox</x-button>
                        </a>
                        <form method="POST" action="{{ route('users.notifications.update', ['user' => $user, 'notification' => $notification])}}">
                            @method('PUT')
                            @csrf
                            <x-button >Mark as unread</x-button>
                        </form>
                    </div>
                    <div class="flex flex-col gap-y-2 p-2">
                        <div style="height:600px">
                            <iframe class="w-full h-full" srcdoc="{{$message->toHtml() }}">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
