<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class TextInput extends Component
{

    /**
     * The name of the question (HTML "name" attribute)
     *
     * @var string
     */
    public string $questionName;

    /**
     * Create a new component instance.
     * @param  String $type (default 'text')
     * @param  String $prefix
     * @param  String $questionNumber
     * @param  String $questionText
     *
     * @return void
     */
    public function __construct(
        public string $prefix,
        public string $questionNumber,
        public string $type='text')
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
        return view('components.inputs.text-input');
    }
}
