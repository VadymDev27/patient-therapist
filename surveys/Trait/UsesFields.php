<?php

namespace Surveys\Trait;

use Closure;
use Illuminate\Support\Collection;
use Surveys\Field;

trait UsesFields
{
    public static function fieldNames(): array
    {
        return collect(static::fields())->map(fn ($field) => $field->names())->flatten()->toArray();
    }

    public function requiredFieldNames(): array
    {
        return collect($this->fields())
            ->filter(fn (Field $field) => $field->isRequired())
            ->map(fn (Field $field) => $field->inputName())
            ->values()
            ->toArray();
    }

    public static function generateFakeData(): array
    {
        $data = collect(static::fields())
                ->map(fn (Field $field) => $field->fakeData() )
                ->collapse(1)
                ->toArray();

        return static::processData($data);
    }

    public static function processData(array $data): array
    {
        foreach (static::fields() as $field) {
            $data = $field->invalidateConditionalFields($data);
        }

        return collect(static::fields())
            ->map(fn (Field $field) => $field->getFieldData($data) )
            ->collapse(1)
            ->toArray();
    }

    private function nullData(): array
    {
        return array_fill_keys(static::fieldNames(), null);
    }

    /**
     * Return data for all the fields from an array of saved data. Returns straight value for regular field and array-ified data for multiselect fields.
     * @param array $data
     *
     * @return array
     */
    public function getViewData(array $data): array
    {
        return collect($this->fields())
            ->map(fn (Field $field) => $field->getFieldValueFromSavedData($data))
            ->collapse(1)
            ->toArray();
    }

    public function getViewFieldInfo(): array
    {
        return collect($this->fields())
            ->map(fn (Field $field) => $field->viewFieldInfo($this->conditionalFieldNames()))
            ->toArray();
    }

    public static function fakeData(): array
    {
        return collect(static::fields())
            ->map(fn (Field $field) => [$field->name => $field->isMultiSelect() ? ['test'] : 'test'])
            ->collapse(1)
            ->toArray();
    }

    private function conditionalFieldNames(): Collection
    {
        return collect($this->fields())
                ->flatMap
                ->conditionalFieldNames();
    }

    public function mainFieldNames(): Collection
    {
        return collect($this->fields())
                ->reject(fn (Field $field) => $this->conditionalFieldNames()->contains($field->name) || $field->isOptional())
                ->map(fn (Field $field) => $field->inputName())
                ->values();
    }

}
