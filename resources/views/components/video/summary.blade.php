@props(['video'])

<div class="border border-gray-300 rounded-lg p-4 ">

    <div class="flex gap-x-6 items-start flex-nowrap justify-between">
        <div class="flex justify-start gap-x-12">

        <div class="font-semibold flex-grow-0 self-center w-max">{{ $video->number }}</div>
        <div class="">
            <div class="text-sm font-semibold p-0">Video</div>
            <div><span>{{ $video->video_title }}</span></div>
        </div>
        @if(! $video->prep)
        <div class="">
            <div class="text-sm font-semibold p-0">Exercises</div>
            <div><span>{{ $video->exercises_title }}</span></div>
        </div>
        @endif
        </div>
        <div class="flex self-center flex-col gap-2">
            <form method="GET" action={{ route('videos.show', ['video' => $video->id])}}>
                <button  class="flex-grow-0 px-4 bg-logo-blue text-white rounded-lg shadow w-full">View video</button>
            </form>

            @if (! $video->prep)
            <form method="GET" action={{ route('download.activities', ['number' => $video->number])}}>
                <button class="px-4 border border-logo-blue text-logo-blue rounded-lg shadow" >Download exercises</button>
            </form>
            @endif

        </div>
    </div>
</div>
