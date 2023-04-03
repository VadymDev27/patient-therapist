<div class="flex flex-col gap-y-3" id="test_user" x-data="testUser" {{ $attributes->merge([]) }}>
    @csrf
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

    @push('scriptlist')
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
    @endpush
</div>
