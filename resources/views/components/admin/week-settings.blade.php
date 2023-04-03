@props(['video'])

<div x-data="{ editing: {{ $video->number === ( $open ?? null ) ? 'true' : 'false'}}, title:'{{ $video->video_title }}', id: {{ $video->video_id }}, exTitle: '{{ $video->exercises_title }}' }" class="border border-gray-300 rounded-lg p-4 ">
    <div  x-show="! editing">

        <div class="flex gap-x-6 items-start flex-nowrap justify-between">
            <div class="flex justify-start gap-x-12">

            <div class="font-semibold flex-grow-0 self-center w-max">{{ $video->number }}</div>
            <div class="">
                <div class="text-sm font-semibold p-0">Video Title</div>
                <div><span x-text="title"></span></div>
            </div>
            <div class="">
                <div class="text-sm font-semibold p-0">Video ID</div>
                <div><span x-text="id"></span></div>
            </div>
            @if(! $video->prep)
            <div class="">
                <div class="text-sm font-semibold p-0">Exercises Title</div>
                <div><span x-text="exTitle"></span></div>
            </div>
            @endif
            </div>
            <button type="button" class="flex-grow-0 px-4 bg-logo-blue text-white rounded-lg shadow self-center" x-on:click="editing=true; fresh=false">Edit</button>
        </div>
    </div>
    <div x-data="{ showPreview: false, showFile: false, file: '{{ $video->activitiesFile() }}' }" x-show="editing" class="p-4">
        <form method="POST" action={{ route('week.update', ['week' => $video->id])}} enctype="multipart/form-data">
            @csrf
            @method('PUT')
        <div class="text-md font-semibold">Week {{ $video->number}} Video</div>
        <x-admin.input name="video_title" x-model="title">
            Video Title:
        </x-admin.input>
        <x-admin.input name="video_id" x-model="id">
            Video ID:
        </x-admin.input>
        <button type="button" x-show="! showPreview" x-on:click="showPreview = true; showFile = false" class="text-blue-500 place-self-start md:ml-60">Preview video</button>
        <template x-if="showPreview">
            <div class="flex flex-col md:ml-60">
                <div class="py-2 w-full">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe x-bind:src="'https://player.vimeo.com/video/' + id" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allowfullscreen></iframe>
                    </div>
                    <button type="button" x-on:click="showPreview = false" class="text-blue-500">Close preview</button>
                </div>


            </div>

        </template>
        @if(! $video->prep)
        <x-admin.input name="exercises_title" x-model="exTitle">
            Weekly exercises title:
        </x-admin.input>

        <x-admin.input name="exercises" type="file" x-on:change="fileChosen">
            Weekly exercises file:
        </x-admin.input>

        <button type="button" x-show="! showFile" x-on:click="showFile = true; showPreview = false" class="text-blue-500 place-self-start md:ml-60">Preview file</button>
        <button type="button" x-show="showFile" x-on:click="showFile = false"  class="text-blue-500 place-self-start md:ml-60">Close preview</button>


        <template x-if="showFile">
        <div class="w-full mt-4">
            <iframe height="600" class="w-full" x-bind:src="file"></iframe>

        </div>
        </template>
        @endif
        <div class="flex float-right mt-4 gap-x-2">
            <button type="button" class="px-4 border border-logo-blue text-logo-blue rounded-lg shadow" x-on:click="editing=false">Close without saving</button>

        <button class="px-4 bg-logo-blue text-white rounded-lg shadow" >Save</button>
        </div>
        </form>
    </div>
</div>
