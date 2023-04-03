
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Portal
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="page" class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold">Preparatory Videos</h2>
                    @if(session('prep-settings-saved'))
                                <div x-show="fresh" class="text-green-600 mb-2">Your changes to the settings for prep video {{  session('prep-settings-saved') }} have been saved successfully.</div>
                    @endif

                    <div class="flex flex-col gap-y-2 mt-4">

                    @foreach($prep as $video)
                        <x-admin.week-settings :video="$video" />
                    @endforeach
                    </div>


                    <h2 class="text-xl font-semibold mt-4">Weekly Videos</h2>
                    @if(session('weekly-settings-saved'))
                                <div x-show="fresh" class="text-green-600 mb-2">Your changes to the settings for week {{  session('weekly-settings-saved') }} have been saved successfully.</div>
                    @endif
                    <div class="flex flex-col gap-y-2 mt-4">
                    @foreach($weekly as $video)
                        <x-admin.week-settings :video="$video" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @push('scriptlist')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('page', () => ({
                fresh: true,

                fileChosen(event) {
                    this.fileToDataUrl(event, src => this.file = src)
                },

                fileToDataUrl(event, callback) {
                if (! event.target.files.length) return

                let file = event.target.files[0],
                    reader = new FileReader()

                reader.readAsDataURL(file)
                reader.onload = e => callback(e.target.result)
                },
            }))
        })
    </script>
    @endpush
</x-app-layout>
