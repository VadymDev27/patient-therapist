<x-layouts.page
    x-data="{ open: false, id: '', email: '', create: false }"
    header="Manage test users"
    with-modal>
    <div class="m-2">
        <p class="text-lg font-semibold">Welcome to the test user admin panel!</p>
        <p>Here you can change the settings for your test users, delete them, or log in as the user.</p>
        <p>A few notes:</p>
        <ul class="list-disc ml-8">
            <li>By using the "log in as user" feature on this page, you gain additional features that are not available to normal users. These include the ability to "time travel" (push the user back in time to give the program the illusion that time has passed), to see all of the database records for the user, and to see all emails sent to the user.</li>
            <li>The "auto time travel" pushes the user back by one week after each week's video and surveys, so that the next week's content will be immediately available.</li>
            <li>The "can go ahead" feature enables the user to continue accessing new content even if their coparticipant is behind.</li>
        </ul>
    </div>
    @cando('create-test')
        <div class="m-2">To create a new test user, click
            <button x-on:click="create=true" class="text-blue-500 underline">here</button>.</div>

    @else
        <div class="m-2">You do not have permission to create new test users. If you need new test users created, please contact the master administrator.</div>
    @endcando
    @if(session('status'))
        <div class="mb-4 text-green-500">
            {{ session('status') }}
        </div>
    @endif

    @if($users->isNotEmpty())
        <div class="flex flex-col sm:flex-row lg:flex-col gap-y-2 gap-x-6 sm:flex-wrap">
        @foreach($users as $user)
            <x-admin.user-summary :user=$user />
        @endforeach
        </div>
    @else
        <div class="m-2">There are currently no test users.</div>
    @endif

    <x-slot name="modal">
        {{-- Create user modal --}}

        <x-admin.modal.base x-cloak x-show="create">
            <form method="POST" action="{{ route('test-users.store') }}" >
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 sm:mt-0 sm:ml-4 ">
                        <h3 class="text-lg leading-6 font-medium sm:font-semibold text-gray-900" id="modal-title">
                        Create new test users
                        </h3>
                        <div class="mt-2">
                            <x-admin.make-test-user class="text-gray-500"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <x-admin.modal.go-button positive>
                    Create user(s)
                </x-admin.modal.go-button>
                <x-admin.modal.cancel-button x-on:click="create=false"/>

            </div>
        </form>
        </x-admin.modal.base>

        {{-- Delete user modal --}}
        <x-admin.modal x-cloak x-show="open">
            Are you sure you want to delete user <span x-text="email"></span>?
            This will also delete their coparticipant and both users' surveys, if applicable.

            <x-slot name="buttons">
                <form method="POST" x-bind:action="'{{ route('test-users.index') }}' + '/' + id">
                    @csrf
                    @method('DELETE')
                <x-admin.modal.go-button>
                    Delete user
                </x-admin.modal.go-button>
                <x-admin.modal.cancel-button x-on:click="open=false"/>
                </form>
            </x-slot>
        </x-admin.modal>
    </x-slot>
</x-layouts.page>
