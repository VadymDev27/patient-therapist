<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $video->video_title }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div  x-data="{ tab: 'info-sheet'}"  class="pb-6  border-b border-gray-200">
                    <div class="sticky top-0 pb-2 bg-white sm:rounded-lg">
                        <div class="xl:px-20 pt-8">
                            <div class="aspect-w-16 aspect-h-9 bg-white">
                                <iframe src="{{ 'https://player.vimeo.com/video/' . $video->video_id }}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allowfullscreen id="player"></iframe>
                            </div>
                        </div>
                        <div class="px-4" >
                        <div class="pb-4  bg-white flex justify-between">
                            <div>
                            <x-link-button href=" {{ route('videos.index') }}" class="text-base">
                                Back to history
                            </x-link-button>
                            </div>
                        </div>
                        <div class="bg-white">
                            <x-tab-button x-on:click="tab = 'transcript'" ::class="{ 'border-indigo-400 text-black': tab === 'transcript', 'hover:border-gray-300 text-gray-500': tab !== 'transcript' }" >Transcript</x-tab-button>
                            <x-tab-button x-on:click="tab='info-sheet'" ::class="{ 'border-indigo-400 text-black': tab === 'info-sheet', 'hover:border-gray-300 text-gray-500': tab !== 'info-sheet'}" >Information Sheet</x-tab-button>
                        </div>
                        </div>
                    </div>
                    <div class="px-4" id="transcript">

                        <x-video.transcript x-show="tab === 'transcript'" :number="$video->number" :prep="$video->prep" />
                        <x-video.info-sheet x-show="tab === 'info-sheet'" :number="$video->number" :prep="$video->prep" />
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
                    number: {{ $video->number }},
                    prep: {{ json_encode($video->prep) }},
                    rewatch: true
                }
                navigator.sendBeacon('{{ route('analytics.store') }}', JSON.stringify($data));
                elapsedTime = 0;
            }
        }


        $( window ).on( "load", function () {


            player = new Vimeo.Player($( "#player"));
            player.on('play', function () {
                startTimer();
                console.log('play');
            });

            player.on('pause', function () {
                pauseTimer();
            });

            player.on('ended', function () {
                sendData();
            });

            player.on('bufferstart', function () {
                pauseTimer();
            });

            player.on('bufferend', function () {
                startTimer();
            });

            lifecycle.addEventListener('statechange', function(event) {
                console.log(event.oldState, event.newState);
                if (event.newState === 'hidden') {
                    player.pause();
                    sendData();
                }
            });


        })

    </script>
    @endpush
</x-app-layout>

