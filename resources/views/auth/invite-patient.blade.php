<x-guest-layout>
    <x-auth.card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo/>
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! The next step is for your patient to fill out the screening survey. 
                Please forward the patient the email we just sent to you, and they can access the survey by clicking the link in the email.
                If you didn\'t receive the email, click the button below and we will send another.') }}
        </div>

        @if (session('status') == 'invitation-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
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

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-auth.card>
</x-guest-layout>
