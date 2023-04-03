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
                    @if($notifications->isNotEmpty())
                    <div class="">
                        Welcome to the email portal! This is not visible to regular users, but is visible to you because you are logged into a test user or admin account. You may click on a message below to read it.
                    </div>
                    <div class="flex flex-col gap-y-2 p-2">
                        <div class="text-lg font-semibold">Messages</div>
                    @foreach ($notifications as $notification)
                        <a href={{ route('users.notifications.show', ['user' => $user, 'notification' => $notification->id]) }}>
                        <div @class(['border border-gray-200 rounded-lg p-2 flex justify-between items-center', 'bg-gray-100' => $notification->read() ])>
                            <div @class([
                                'font-semibold' => $notification->unread(),
                            ])>
                                {{ $notification->data['subject'] ?: Str::title(
                                    Str::snake(class_basename($notification->type), ' '))
                                 }}
                            </div>
                            <div class="text-sm">
                                sent {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                        </a>
                    @endforeach
                    </div>
                    @else
                    <div class="">
                        Welcome to the email portal! This is not visible to regular users, but is visible to you because you are logged into a test user or admin account. This user currently has no messages, but once reminders or other messages are sent, they will appear here.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
