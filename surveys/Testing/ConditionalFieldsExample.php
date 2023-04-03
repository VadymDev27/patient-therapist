<?php

namespace Surveys\Testing;

use Surveys\Field;
use Surveys\Trait\UsesFields;

class ConditionalFieldsExample
{
    use UsesFields;
    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fields(): array
    {
        return [
            Field::make('test')->conditionalFields(['test2'],'Yes'),
            Field::make('test2'),
            Field::make('test3')->conditionalFields(['test4','test5'], 'Yes'),
            Field::make('test4'),
            Field::make('test5')
        ];
    }
}
