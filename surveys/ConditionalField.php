<?php

namespace Surveys;

class ConditionalField
{
    public function __construct(
        private array $dependentFields, private mixed $targValue, private Field $field
    )
    {
    }

    public function shouldInvalidateConditionals(array $data): bool
    {
        $value = collect(data_get($data, $this->field->name));

        return collect($this->targValue)
                ->filter(fn ($option) => $value->contains($option))
                ->isEmpty();
    }

    public function nullData(): array
    {
        return array_fill_keys($this->dependentFields, null);
    }

    public function fieldNames(): array
    {
        return $this->dependentFields;
    }
}
