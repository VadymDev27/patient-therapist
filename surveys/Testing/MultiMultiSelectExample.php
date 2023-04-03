<?php

namespace Surveys\Testing;

use Surveys\Field;
use Surveys\Trait\UsesFields;

class MultiMultiSelectExample
{
    use UsesFields;
      /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fields(): array
    {
        return [
            Field::make('test_1')->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4'])->conditionalFields(['test_2'], ['Option3', 'Option 2'])->required(),
            Field::make('test_2'),
            Field::make('test_3')->conditionalFields(['test_4','test_5'], 'Yes'),
            Field::make('test_4')->multiSelect(['Option 1', 'Option 2']),
            Field::make('test_5')->required()
        ];
    }
}
