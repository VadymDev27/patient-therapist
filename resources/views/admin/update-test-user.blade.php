<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User Details: {{ $user->email }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-1  md:grid-cols-9 lg:grid-cols-12 sm:gap-4 gap-y-4">
                    <div class="border border-gray-200 rounded-lg p-4 md:col-span-4">
                        <div class="font-semibold text-lg">User data</div>
                        <table >
                        @foreach ($user->summary() as $key => $value)
                        <tr>
                        <td class="px-2 text-right whitespace-nowrap">{{ "{$key}:" }}</td>
                        <td class="">{{ $value }}</td>
                        </tr>
                        @endforeach
                        </table>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4 md:col-span-5">
                        <div class="font-semibold text-lg">Time travel</div>
                        <div class="">You may send the user "forward in time" in order to trigger program events such as milestone surveys. Use the selectors below to select how far forward you would like to push the user, and click the button to "time travel".</div>
                        <form method="POST" action="{{ route('test-users.update', ['user' => $user->id ])}}" class="flex flex-col items-center my-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <select class="border-0 border-b" name="years">
                                    <option value=""></option>
                                    @foreach(range(0,3) as $num)
                                        <option value="{{ $num }}">{{ $num }}</option>
                                    @endforeach
                                </select> years,
                                <select class="border-0 border-b" name="months">
                                    <option value=""></option>
                                    @foreach(range(0,12) as $num)
                                        <option value="{{ $num }}">{{ $num }}</option>
                                    @endforeach
                                </select> months,
                                <select class="border-0 border-b" name="weeks">
                                    <option value=""></option>
                                    @foreach(range(0,4) as $num)
                                    <option value="{{ $num }}">{{ $num }}</option>
                                    @endforeach
                                </select> weeks
                            </div>
                            <x-button>Update user timeline</x-button>
                        </form>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 col-span-full lg:col-span-3 ">
                        <div class="font-semibold text-lg">Actions</div>
                        <div class="flex flex-col sm:flex-row lg:flex-col gap-y-4 mt-2 gap-x-4">
                            <form method="POST" action="{{ route('test-users.login',['user' => $user->id])}}" class="contents">
                                @csrf
                                @group('admin')
                                <x-button no-gap class="lg:w-full">Log in as user</x-button>
                                @endgroup
                            </form>
                            <form method="POST" action="{{ route('test-users.update',['user' => $user->id, 'action' => 'toggle_time_travel'])}}" class="contents">
                                @method('PUT')
                                @csrf
                                <x-button no-gap class="lg:w-full">
                                    {{ $user->test_time_travel ? 'Disable auto time travel' : 'Enable auto time travel'}}
                                </x-button>
                            </form>
                            <form method="POST" action="{{ route('test-users.update',['user' => $user->id, 'action' => 'toggle_can_go_ahead'])}}" class="contents">
                                @method('PUT')
                                @csrf
                                <x-button no-gap class="lg:w-full">
                                    {{ $user->test_can_go_ahead ? 'Disable outpacing coparticipant' : 'Enable outpacing coparticipant'}}
                                </x-button>
                            <form>
                            <a href="{{ route('users.notifications.index', ['user' => $user->id]) }}"  class="contents">
                            <x-button type="button" no-gap class="lg:w-full">View emails</x-button>
                            </a>
                        </div>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 col-span-full">
                        <div class="font-semibold text-lg">Surveys</div>
                        @foreach ($user->surveys as $survey)
                        <div class='border border-gray-200 rounded-lg p-2 flex justify-between items-center gap-x-4'>
                            <div>
                                {{ $survey->nameForHumans() }}
                            </div>
                            <div class="text-sm">
                                {{ $survey->isComplete() ? $survey->completed_at->diffForHumans() : 'in progress'}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
