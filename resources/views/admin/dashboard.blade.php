<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Portal
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Welcome to the TOP DD RCT Admin Dashboard! Please click the buttons below or above in the navigation bar to perform actions.

                    <div class="flex flex-col min-w-max max-w-sm">
                        @cando('download-data')
                        <x-link-button :href="route('data')" class="text-center">
                            {{ __('Download Data') }}
                        </x-link-button>
                        @endcando
                        @cando('edit-settings')
                        <x-link-button :href="route('week.index')" class="text-center">
                            {{ __('Edit Weekly Settings') }}
                        </x-link-button>
                        @endcando
                        @candoany(['edit-admin','invite-admin','delete-admin'])
                        <x-link-button :href="route('admin.users.index')" class="text-center">
                            {{ __('Manage Admin Users') }}
                        </x-link-button>
                        @endcando
                        <x-link-button :href="route('test-users.index')" class="text-center">
                            {{ __('Access Test Users') }}
                        </x-link-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
