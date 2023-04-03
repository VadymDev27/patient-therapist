<x-layouts.page header="Preview Surveys">
<div class="mb-4">
    Welcome! Below you can find preview versions of all of the surveys that patients will be presented with throughout the study.
</div>

<div class="flex flex-col gap-y-4">
@foreach($surveyList as $category => $surveys)
    <div>
        <div class="text-lg font-bold">{{ $category }}</div>
        <ul class="list-circle list-inside">
        @foreach($surveys as $title => $slug)
            <li>
            <a href="{{ route('preview-survey.show', ['role' => 'patient', 'category' => $category, 'slug' => $slug])}}" class="text-blue-600 underline visited:text-purple-600">
                {{ $title }}
            </a>
            </li>
        @endforeach
        </ul>
    </div>
@endforeach
</div>
</x-layouts.page>
