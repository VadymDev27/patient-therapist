<?php

namespace App\Surveys\Patient\Steps\Milestone;

use Surveys\Field;
use Surveys\SurveyStep;


class DESrequired extends DES
{
    protected static function fields(): array
    {
        return array_map(
            fn (Field $field) => $field->required(),
            parent::fields()
        );
    }

    public static function publicFields()
    {
        return static::fields();
    }
}
