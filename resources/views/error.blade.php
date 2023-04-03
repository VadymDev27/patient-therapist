<x-landing-layout>
    <section class="flex h-48">
        <img src={{ asset( 'lavender_wide.jpg' ) }} class="object-cover w-full h-full relative z-0"/>
    </section>
    <section class="text-gray-600 body-font">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="container mx-auto flex px-5 py-16 md:flex-row flex-col items-center">
                <div class="lg:flex-grow lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                    Oops, something went wrong!
                </h1>
                <p class="mb-4 leading-relaxed">There was a problem with your request. Click below to return to your home page.</p>
                <div class="flex justify-center">
                    @guest
                    <x-nav-button-primary :href="url('/')" class="text-base">
                        Return home
                    </x-nav-button-primary>
                    @endguest
                    @group('user')
                    <x-nav-button-primary :href="route('dashboard')" class="text-base">
                        Return to dashboard
                    </x-nav-button-primary>
                    @elsegroup('admin')
                    <x-nav-button-primary :href="route('admin.dashboard')" class="text-base">
                        Return to dashboard
                    </x-nav-button-primary>
                    @endgroup
                </div>
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
