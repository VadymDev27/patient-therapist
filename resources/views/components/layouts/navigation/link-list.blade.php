@props(['component' => 'nav-link'])


@group('admin')
    <x-dynamic-component :component=$component :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
    {{ __('Dashboard') }}
    </x-dynamic-component>

    @cando('download-data')
    <x-dynamic-component :component=$component :href="route('data')" :active="request()->routeIs('data')">
        {{ __('Download Data') }}
    </x-dynamic-component>
    @endcando

    @cando('edit-settings')
    <x-dynamic-component :component=$component :href="route('week.index')" :active="request()->routeIs('week.index')">
        {{ __('Edit weekly settings') }}
    </x-dynamic-component>
    @endcando

    <x-dynamic-component :component=$component :href="route('test-users.index')" :active="request()->routeIs('test-sers.index')">
        {{ __('Test Users') }}
    </x-dynamic-component>
@elsegroup('user')
    <x-dynamic-component :component=$component :href="route('dashboard')" :active="request()->routeIs('dashboard')">
    {{ __('Dashboard') }}
    </x-dynamic-component>
    @if(! Auth::user()->accessExpired())
        @if( request()->user()->can('viewAny', \App\Models\WeeklySettings::class))
            <x-dynamic-component :component=$component :href="route('videos.index')" :active="request()->routeIs('videos.index')">
                {{ __('History') }}
            </x-dynamic-component>
        @endif
        @if(! request()->user()->hasCompletedSurvey('discontinuation'))
        <x-dynamic-component :component=$component :href="request()->user()->getSurveyUrl('discontinuation')" :active="request()->segment(3) === 'discontinuation'">
            {{ __('Discontinuation Survey') }}
        </x-dynamic-component>
        @endif
    @endif

    @test()
    <x-dynamic-component :component=$component :href="route('users.notifications.index', ['user' => Auth::id()])" :active="request()->routeIs('users.notifications.index')">
        {{ __('Mailbox')  }}
        @php $unread = Auth::user()->unreadNotifications()->count() @endphp
        @if ($unread > 0)
        <span class="inline-flex items-center justify-center px-2 py-1 mx-2 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $unread }}</span>
        @endif
    </x-dynamic-component>
    <x-dynamic-component :component=$component :href="route('test-users.edit', ['user' => Auth::id()])" :active="request()->routeIs('test-users.edit')">
        {{ __('Admin Panel') }}
    </x-dynamic-component>
    @endtest

@endgroup
<x-dynamic-component :component=$component :href="'https://www.topddstudy.com'" :active="false">
    {{ __('Lab Website') }}
</x-dynamic-component>
