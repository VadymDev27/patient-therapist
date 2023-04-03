{{--  TODO: update navigation --}}

<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-20" />
                    </a>
                </div>

                <!-- Navigation Links-->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 md:flex">
                    <x-layouts.navigation.link-list />
                </div>
            </div>

            <div class="hidden md:flex items-center">
                @auth
                <div class="items-center p-2">
                    <form method="POST" action="{{ $route = Auth::user()->logoutLink() }}">
                        @csrf
                        <x-nav-button-primary :href="$route"
                            onclick="event.preventDefault();
                            this.closest('form').submit();">
                            Log out
                        </x-nav-button-primary>
                    </form>
                </div>
                @else
                    <div class="items-center p-2">
                        <a href={{ route('login') }} class="inline-block text-sm px-4 py-2 leading-none border rounded text-logo-blue hover:underline border-logo-blue hover:border-indigo-900 hover:text-indigo-900 mt-4 lg:mt-0">Log in</a>
                    </div>
                    <div class="items-center p-2">
                        <x-nav-button-primary :href="route('register')">
                            {{ __('Register') }}
                        </x-nav-button-primary>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-layouts.navigation.link-list component="responsive-nav-link" />
            @auth
            <form class="contents" method="POST" action="{{ Auth::user()->logoutLink() }}">
                @csrf
                <x-responsive-nav-link button>Logout</x-responsive-nav-link>
            </form>
            @endauth
            @guest
                <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')" :active="false">
                    {{ __('Register') }}
                </x-responsive-nav-link>
            @endguest
        </div>
    </div>
</nav>
