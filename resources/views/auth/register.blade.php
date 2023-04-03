<x-guest-layout>
    <x-auth.card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            @if ($therapistId)
            <div class="flex flex-col gap-y-2">
                <div class="">
                    Welcome to the screening process for the TOP DD Network Randomized Controlled Trial (RCT)!
                </div>
                <div class="">
                    Your therapist has indicated that you are interested in participating in the TOP DD Network RCT, a study that provides participants with access to information about ways to help yourself reduce emotional overwhelm (“feeling too much”), PTSD symptoms, and dissociation.  (For more information, go to <a class="text-blue-500 underline" href="https://www.TOPDDStudy.net">TOPDDStudy.net</a>.)
                </div>
                <div class="">
                    To complete your portion of the eligibility screening process, please begin by entering your information below to register an account.
                </div>
            </div>
            @else
            Please enter your information below to create an account. Note that this sign-up page is ONLY for therapists.  If you are a patient, please ask your therapist to come to our site to complete the screening survey.
            @endif
        </div>

        <!-- Validation Errors -->
        <x-auth.validation-errors class="mb-4" :errors="$errors" />

        @if ($therapistId)
            <form method="POST">
        @else
            <form method="POST">
        @endif
            @csrf

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">

                <x-button class="ml-4">
                    {{ __('Create Account') }}
                </x-button>
            </div>
        </form>
    </x-auth.card>
</x-guest-layout>
