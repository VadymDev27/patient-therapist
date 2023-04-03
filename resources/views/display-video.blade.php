@php
    $number = $step['details']['number'];
    $prep = $step['details']['prep'];
    $title = $step['details']['videoTitle'];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ $prep ? 'Preparatory Videos' : 'Weekly Activities' }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div  x-data="{ tab: 'info-sheet'}"  class="pb-6 bg-white border-b border-gray-200">
                    <div class="font-semibold text-xl underline p-4"> {{ $title }}</div>
                    <div class="sticky top-0 bg-white pb-2">
                        <div class="xl:px-20">
                        <div class="aspect-w-16 aspect-h-9">
                            <iframe src="{{ 'https://player.vimeo.com/video/' . data_get($step, 'details.videoId') }}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allowfullscreen id="player"></iframe>
                        </div>
                        </div>
                        <div class="px-4" >
                        <div class="pb-4  bg-white w-full">
                            <div class="flex justify-between">
                                @if (!! $step['backUrl'])
                                    <x-link-button :href="$step['backUrl']" class="text-base">
                                        Back
                                    </x-link-button>
                                @else
                                    <div class=""></div>
                                @endif
                                <form method="POST" action="{{ $step['postUrl'] }}" id="survey-form" class="inline-block">
                                    @csrf
                                <x-button id="submit-button" disabled>{{ $step['buttonMessage'] }} </x-button>
                                </form>
                            </div>
                            <span id="wait-message" class="hidden sm:block float-right text-sm text-gray-700">Please complete the video to move forward</span>

                        </div>

                        <div>
                            <x-tab-button x-on:click="tab = 'transcript'" ::class="{ 'border-indigo-400 text-black': tab === 'transcript', 'hover:border-gray-300 text-gray-500': tab !== 'transcript' }" >Transcript</x-tab-button>
                            <x-tab-button x-on:click="tab='info-sheet'" ::class="{ 'border-indigo-400 text-black': tab === 'info-sheet', 'hover:border-gray-300 text-gray-500': tab !== 'info-sheet'}" >Information Sheet</x-tab-button>
                        </div>
                        </div>
                    </div>
                    <div class="px-4" id="transcript">

                        <x-video.transcript x-show="tab === 'transcript'" :number="$number" :prep="$prep" />
                        <x-video.info-sheet x-show="tab === 'info-sheet'" :number="$number" :prep="$prep" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scriptlist')
    <script src="https://player.vimeo.com/api/player.js"></script>
    <script>
        let start;
        let playing = false;
        let elapsedTime = 0;

        function startTimer() {
            if (! playing) {
                start = new Date().getTime();
                playing = true;
            }
        }

        function pauseTimer() {
            if (playing) {
                playing = false;
                elapsedTime += new Date().getTime() - start;
                start = null;
            }
        }

        function sendData() {
            pauseTimer();
            if (elapsedTime > 0) {
                $data = {
                    elapsed_time: elapsedTime,
                    number: {{ $step['details']['number'] }},
                    prep: {{ json_encode($step['details']['prep']) }},
                    rewatch: false
                }
                navigator.sendBeacon('{{ route('analytics.store') }}', JSON.stringify($data));
                elapsedTime = 0;
            }
        }


        $( window ).on( "load", function () {


            player = new Vimeo.Player($( "#player"));
            player.on('play', function () {
                startTimer();
            });

            player.on('pause', function () {
                pauseTimer();
            });

            player.on('ended', function () {
                sendData();
                $("#submit-button").prop('disabled',false);
                $("#wait-message").remove()
            });

            player.on('bufferstart', function () {
                pauseTimer();
            });

            player.on('bufferend', function () {
                startTimer();
            });

            lifecycle.addEventListener('statechange', function(event) {
                if (event.newState === 'hidden') {
                    player.pause();
                    sendData();
                }
            });


        })

    </script>
    @endpush
</x-app-layout>

