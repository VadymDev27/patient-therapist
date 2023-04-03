<?php

namespace Surveys;

class Test
{
    /**
     * Returns the names of all of the question fields (NOT including prefix)
     */
    protected static function fields(): array
    {
        return [
            Field::make('test')->conditionalFields(['test2'],'Yes'),
            Field::make('test2')
        ];
    }

    public static function fieldNames(): array
    {
        return collect(static::fields())->map(fn ($field) => $field->name)->toArray();
    }
}
