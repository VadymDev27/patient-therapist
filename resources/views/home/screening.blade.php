<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col gap-y-2">
                    <div class="">Thank you for your interest in the TOP DD Network RCT! </div>
                    <div class="">Click the button below to access the screening survey.</div>
                    <div class="flex items-center justify-between">
                        <form method="GET" action="{{ $screeningUrl }}">
                            @csrf

                            <div>
                                <x-button no-gap>
                                    {{ __('Take screening survey') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
