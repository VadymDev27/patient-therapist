<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Download
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" x-data="{
                    message: '',
                    refresh: false,
                    refreshing: false,
                    generateData() {
                        this.message='Please wait, generating new data files...'
                        this.refresh=true
                        this.refreshing=true
                        fetch('{{ route('data.generate') }}', {
                            method: 'GET'
                        })
                        .then(data => {
                            this.message = 'Data refreshed successfully. Click below to download.'
                            this.refreshing = false
                        })
                    }
                }">
                    <div><span x-text="message"></span></div>
                    <div x-show="! refresh">
                    On this page, you can download the data collected so far for the TOP DD RCT. The data file was last updated {{ $time }}.
                    You can manually refresh the data by clicking here:
                    <button type="button" x-on:click="generateData" class="text-blue-500 underline">refresh data</button>.
                    </div>

                    <form method="GET" action="{{ route('data.download')}}">
                        <x-button ::disabled="refreshing">Download data</x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
