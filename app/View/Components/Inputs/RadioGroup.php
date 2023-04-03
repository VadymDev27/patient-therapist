<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class RadioGroup extends Component
{
    /**
     * The name of the question (HTML "name" attribute)
     *
     * @var string
     */
    public $questionName;

    /**
     * Determines if the value should be selected based on flashed old data
     *
     * @param  string  $value
     * @return bool
     */
    public function isChecked($value) {
        return old($this->questionName) === $value;
    }

    /**
     * Create a new component instance.
     * @param  string  $questionName
     * @param  string  $questionText
     * @param  array  $answers
     * @return void
     */
    public function __construct(
        public string $prefix,
        public string $questionNumber,
        public string $questionText,
        public iterable $answers)
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
        return view('components.inputs.radio-group');
    }
}
