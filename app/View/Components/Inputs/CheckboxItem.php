<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class CheckboxItem extends Component
{

    /**
     * The question number
     *
     * @var string
     */
    public $questionNumber;

    /**
     * The answer number (i.e. where in the list of answers is it).
     * This will also serve as the value for the checkbox.
     *
     * @var string
     */
    public $answerNumber;

    /**
     * The name of the question (HTML "name" attribute)
     *
     * @var string
     */
    public $questionName;

    /**
     * The text of the answer
     *
     * @var string
     */
    public $answerText;

    /**
     * Determines if the value should be selected based on flashed old data
     *
     * @param  string  $value
     * @return bool
     */
    public function isChecked($value) {
        if (! old($this->questionName)) {
            return false;
        }
        return in_array($value,old($this->questionName));
    }

    /**
     * Create a new component instance.
     * @param  string  $questionName
     * @param  string  $questionText
     * @param  array  $answers
     * @param  bool $hasHiddenOptions
     * @return void
     */
    public function __construct($questionName, $answerText, $answerNumber)
    {
        $this->questionName = $questionName;
        $this->answerText = $answerText;
        $this->answerNumber = $answerNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inputs.checkbox-item');
    }
}
