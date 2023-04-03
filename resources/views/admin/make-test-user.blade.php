

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create test user
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" x-data="testUser">
                    <div class="">Please use the options below to create a test user. Note that the user will be created as though they reach whatever stage you select TODAY - meaning, users assigned to the waitlist will have a randomization date of today, users who are set at week 10 will have completed week 9 today.
                    <div>
                        <form method="POST" action="{{ route('test-users.store') }}" class="mt-4 max-w-sm p-4 border border-gray-200 rounded-lg flex flex-col gap-y-3" id="test_user">
                            @csrf
                            <div class="text-lg font-bold">Customize test user</div>
                            <div>
                                <div class="font-semibold">Patient finished with screening?</div>
                                @foreach(['yes', 'no'] as $option)
                                <div>
                                    <input type="radio" id="{{ 'screening_' . $option }}" name="patient_screening" value="{{ $option }}" x-model="screening" />
                                    <label for="{{ 'screening_' . $option }}">{{ Str::ucfirst($option) }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div x-show="screening==='no'">
                                <div class="font-semibold">Therapist finished with screening?</div>
                                @foreach(['yes', 'no'] as $option)
                                <div>
                                    <input type="radio" id="{{ 'tscreening_' . $option }}" name="therapist_screening" value="{{ $option }}" x-model="therapistScreening" />
                                    <label for="{{ 'tscreening_' . $option }}">{{ Str::ucfirst($option) }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div x-show="screening==='yes'">
                                <div class="font-semibold">Randomized?</div>
                                @foreach(['yes', 'no'] as $option)
                                <div>
                                    <input type="radio" id="{{ 'randomized_' . $option }}" name="randomized" value="{{ $option }}" x-model="randomized"/>
                                    <label for="{{ 'randomized_' . $option }}">{{ Str::ucfirst($option) }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div x-show="randomized==='yes'">
                                <div class="font-semibold">Group:</div>
                                @foreach(['immediate access', 'waitlist'] as $option)
                                <div>
                                    <input type="radio" id="{{  $option }}" name="waitlist" value="{{ $loop->index }}" x-model="waitlist"/>
                                    <label for="{{ $option }}">{{ Str::ucfirst($option) }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div x-show="waitlist==='0'">
                                <div class="font-semibold">Week number</div>
                                <input class="md:mt-1 show w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50v" type="number" value="0" name="week" />
                            </div>
                            <div class="" x-show="therapistScreening==='no'">
                                Due to the options selected, only a therapist test user will be created.
                            </div>
                            <x-button>Create user</x-button>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('testUser', () => ({

                role: '',

                randomized: '',

                screening: '',

                therapistScreening: '',

                waitlist: '',

                coRole: '',

                setCoRole() {
                    switch (this.role) {
                        case 'therapist':
                            this.coRole = 'patient';
                        case 'patient':
                            this.coRole = 'therapist';

                    }
                    console.log('role: ' + this.role)
                    console.log(this.coRole)
                }
            }))
        })
    </script>
</x-app-layout>
