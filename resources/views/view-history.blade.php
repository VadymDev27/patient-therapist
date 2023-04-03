<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Video and Activity History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Preparatory Videos</h2>

                    <div class="flex flex-col gap-y-2 mt-4">
                        @foreach($prep as $video)
                            <x-video.summary :video="$video" />
                        @endforeach
                    </div>

                    @if(count($weekly) > 0)
                    <h2 class="text-xl font-semibold mt-4">Weekly Videos</h2>

                    <div class="flex flex-col gap-y-2 mt-4">
                        @foreach($weekly as $video)
                            <x-video.summary :video="$video" />
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
