<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $subject ?? __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col gap-y-2">
                    @if (isset($greeting))
                        <div class="text-lg font-semibold">
                            {{ $greeting }}
                        </div>
                    @endif

                    @foreach ($introLines as $line)
                        <div>{{ $line }}</div>
                    @endforeach


                    @if (isset($actionText))
                        <div class="">

                            @if (isset($statusCode) && session('status') === $statusCode)
                                <div class="font-medium text-sm text-green-600">
                                    {{ $statusText }}
                                </div>
                            @endif
                            <form method="{{ $method ?? 'GET' }}" action="{{ $actionUrl }}">
                                @csrf

                                <div>
                                    <x-button no-gap>
                                        {{ $actionText }}
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    @endif


                    {{-- Outro Lines --}}
                    @foreach ($outroLines as $line)
                    <div>{{ $line }}</div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
