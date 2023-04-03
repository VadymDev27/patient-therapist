<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class RadioButtonResponsive extends Component
{
    /**
     * The name of the question (HTML "name" attribute)
     * 
     * @var string
     */
    public $questionName;
    
    /**
     * The numeric value of the question
     * 
     * @var string
     */
    public $value;

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
     *
     * @return void
     */
    public function __construct($questionName, $value)
    {
        $this->questionName = $questionName;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inputs.radio-button-responsive');
    }
}
