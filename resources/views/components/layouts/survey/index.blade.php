@props(['step'])

<x-layouts.page :header="$step['surveyTitle']"
    x-data="{
        highlightIncomplete: false,
        highlightMain: false,
        openRequired: false,
        openMain: false,
        required: {{ json_encode($step['requiredFields']) }},
        mainFields: {{ json_encode($step['mainFields']) }},
        isFilled( fields ) {
            const data = $($refs.survey).serializeArray()
            return fields.every(name =>  {
                return data.some(d => d.name === name && d.value != '')
            })
        },
        checkFields() {
            if (! this.isFilled(this.required)) {
                this.openRequired = true;
                return false;
            } else if (! this.isFilled(this.mainFields)) {
                this.openMain = true;
                return false;
            }
            return true
        }
    }"
    with-modal >
    <form method="POST" action="{{ $step['postUrl'] }}" x-ref="survey" id="survey">
        @csrf

        {{ $slot }}

        <div class="mt-4">
        @if (!! $step['backUrl'])
            <x-link-button :href="$step['backUrl']" class="text-base">
                Back
            </x-link-button>
        @endif
        <x-button x-on:click.prevent="checkFields() && $refs.survey.submit()">{{ $step['buttonMessage'] }} </x-button>
    </div>
    </form>

    @if ($step['category'] !== 'consent' && $step['index'] % 2 === 1)
        <x-layouts.survey.footer />
    @endif

    <x-slot name="modal">
        <x-admin.modal x-cloak x-show="openRequired">
            {{ $requiredMessage ?? "Survey questions required for determining eligibility are currently left blank. Are you sure you want to continue? Please note that if you submit the survey with these questions left blank, you will be marked ineligible to participate in the TOP DD Network RCT." }}

            <x-slot name="buttons">
                <x-admin.modal.go-button x-on:click="openRequired=false; highlightIncomplete=true" positive >
                    Return to page
                </x-admin.modal.go-button>
                <x-admin.modal.cancel-button x-on:click="$refs.survey.submit()" text="Continue" />
            </x-slot>
        </x-admin.modal>
        <x-admin.modal x-cloak x-show="openMain" icon-color="blue" title-text="Note:">
            We wanted to make sure you were aware that you left some questions unanswered. If this was unintentional, and/or you are willing to complete these, we very much appreciate your completing them: Your answers help us help dissociative patients and their therapists.

            <x-slot name="buttons">
                <x-admin.modal.go-button x-on:click="openMain=false; highlightMain=true" positive >
                    Return to page
                </x-admin.modal.go-button>
                <x-admin.modal.cancel-button x-on:click="$refs.survey.submit()" text="Continue" />
            </x-slot>
        </x-admin.modal>
    </x-slot>
</x-layouts.page>
