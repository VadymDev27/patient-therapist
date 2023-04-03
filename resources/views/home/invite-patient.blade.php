<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        {{__('messages.eligible')}}
                    </div>
                    <div class="mb-4 ">
                         {{ __('messages.invite-patient') }}
                    </div>

                @if (session('status') == 'invitation-link-sent')
                    <div class="mb-4 font-medium text-base text-green-600">
                        {{ __('A new invitation link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                    <div class="mt-4 flex items-center justify-between">
                        <form method="POST" action="{{ route('patient-invitation.send') }}">
                            @csrf

                            <div>
                                <x-button>
                                    {{ __('Resend Patient Invitation Email') }}
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
