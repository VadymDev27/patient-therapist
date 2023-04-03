<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Portal
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Preparatory Videos</h2>
                    <div class="grid grid-cols-2 gap-2">
                    @foreach ($prep as $video)
                    <div class="border border-gray-300 rounded md p-4"  x-data="{ title: '{{ $video['video_title']}}', id: '{{ $video['video_id']}}', showPreview: false }">
                        <div class="text-md font-semibold">Prep Video #{{ $video['number']}}</div>
                        <div class="flex flex-col">
                            <div class="text-gray-700 md:flex md:items-center mt-2">
                                <div class="mb-0 md:w-40 md:text-right px-4">
                                <label for="forms-labelLeftInputCode">Video Title:</label>
                                </div>
                                <div class="md:w-2/3 md:flex-grow">
                                <input class="md:mt-1
                                show
                                w-full
                                rounded-md
                                border-gray-300
                                shadow-sm
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50v" type="text" id="forms-labelLeftInputCode"  x-model="title"/>
                                </div>
                            </div>
                            <div class="text-gray-700 md:flex md:items-center mt-2">
                                <div class="mb-0 md:w-40 md:text-right px-4">
                                <label for="forms-labelLeftInputCode">Video ID:</label>
                                </div>
                                <div class="md:w-2/3 md:flex-grow">
                                    <input class="md:mt-1
                                    show
                                    w-full
                                    rounded-md
                                    border-gray-300
                                    shadow-sm
                                    focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50v" type="text" id="forms-labelLeftInputCode" x-model="id"/>
                                </div>
                            </div>
                            <button x-show="! showPreview" x-on:click="showPreview = true" class="text-blue-500 place-self-start md:ml-40">Preview video</button>
                            <template x-if="showPreview">
                                <div class="py-2">
                                <div class="aspect-w-16 aspect-h-9">
                                    <iframe x-bind:src="'https://player.vimeo.com/video/' + id" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen allowfullscreen></iframe>
                                </div>
                                </div>
                            </template>
                            <button x-show="showPreview" x-on:click="showPreview = false" class="text-blue-500">Close preview</button>

                        </div>

                    </div>
                    @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
