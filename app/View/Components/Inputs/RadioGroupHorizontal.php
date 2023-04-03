<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class RadioGroupHorizontal extends Component
{
    /**
     * The name of the question (HTML "name" attribute)
     *
     * @var string
     */
    public $questionName;

    /**
     * Create a new component instance.
     * @param  string  $prefix (This should include the underscore)
     * @param  string  $questionNumber
     * @param  string  $questionText
     * @param  string   $scaleOptions -- key is number & the value of the radio button, value is the label underneath
     * @return void
     */
    public function __construct(
            public string $prefix,
            public string $questionNumber,
            public string $questionText,
            public iterable $scaleOptions
    )
    {
        $this->questionName = $prefix . $questionNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inputs.radio-group-horizontal');
    }
}
