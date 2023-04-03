<?php

namespace Surveys;

use Closure;
use Faker\Generator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Field
{
    public const TYPE_NUMBER = 1;

    public const TYPE_TEXTAREA = 2;

    private bool $required = false;

    private int $type = 0;

    private ?string $otherName = null;
    /**
     * Possible answer values of a multi-select input such as a checkbox group.
     *
     * @var array
     */
    private array $multiSelectOptions = [];

    /**
     * Possible answer values of a radio group. This gets passed to the view but does not affect data storage like multiSelectOptions does.
     *
     * @var array
     */
    private array $radioOptions = [];

    private string $question = '';

    /**
     * Conditional field objects, which store the dependent fields and the value(s) that allow those fields
     * @var Collection
     */
    private Collection $conditionalFields;

    private Generator $faker;

    private bool $optional = false;

    public function __construct(
        public string $name
    ) {
        $this->conditionalFields = collect([]);
        $this->faker = app()->make(Generator::class);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public static function closure(): Closure
    {
        return fn ($name) => static::make($name);
    }

    public function conditionalFields(mixed $conditionalFields, mixed $targValue): Field
    {
        $this->conditionalFields
            ->push(new ConditionalField(Arr::wrap($conditionalFields), $targValue, $this));

        return $this;
    }

    public function withHiddenOther()
    {
        $this->otherName = $this->name . '_Other';
        $this->conditionalFields
            ->push(new ConditionalField([$this->otherName], 'Other', $this));
        return $this;
    }

    public function multiSelect(array $multiSelectOptions): Field
    {
        $this->multiSelectOptions = $multiSelectOptions;

        return $this;
    }

    public function radio(array $radioOptions): Field
    {
        $this->radioOptions = $radioOptions;

        return $this;
    }

    public function type(int $type): Field
    {
        $this->type = $type;

        return $this;
    }

    public function required(): Field
    {
        $this->required = true;

        return $this;
    }

    public function optional(): Field
    {
        $this->optional = true;

        return $this;
    }

    public function question(string $question): Field
    {
        $this->question = $question;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function conditionalFieldNames(): array
    {
        return $this->conditionalFields
                    ->flatMap->fieldNames()->toArray();
    }

    public function invalidateConditionalFields(array $data): array
    {
        return array_merge($data, $this->getNulledFields($data));
    }

    public function getFieldData(array $data): array
    {
        return array_merge(
            [$this->name => $this->getFieldValue($data)],
            $this->expandMultiSelectData($data),
            $this->getOtherValueArray($data)
        );
    }

    public function fakeData(): array
    {
        return array_merge($this->fakeMainFieldData(), $this->fakeOtherFieldData());
    }

    private function fakeOtherFieldData(): array
    {
        return $this->otherName
                ? [ $this->otherName => $this->faker->words(3,true)]
                : [];
    }

    private function fakeMainFieldData(): array
    {
        $value = $this->fakeMainFieldValue();
        return is_null($value) ? [] : [$this->name => $value];
    }

    private function fakeMainFieldValue(): mixed
    {
        // give 2% chance of being empty
        if (rand(1,50) === 1 && (! $this->isRequired())) {
            return null;
        }
        if ($this->isMultiSelect()) {
            return Arr::random($this->multiSelectOptions, $this->getRandomNumberSelected());
        }
        if ($this->radioOptions) {
            if (Arr::isAssoc($this->radioOptions)) {
                return Arr::random(array_keys($this->radioOptions));
            }
            return Arr::random($this->radioOptions);
        }
        if ($this->type === self::TYPE_NUMBER) {
            return rand(1,30);
        }
        if ($this->type === self::TYPE_TEXTAREA) {
            return $this->faker->paragraph();
        }

        return $this->faker->word();
    }

    private function getRandomNumberSelected(): int
    {
        $max = count($this->multiSelectOptions);
        $max = $max > 5 ? 5 : $max;
        return rand(1, $max);
    }

    public function names(): array
    {
        return array_merge(
            Arr::wrap($this->name),
            $this->multiSelectFieldNames(),
            Arr::wrap($this->otherName));
    }

    public function getNulledFields(array $data): array
    {
        return $this->conditionalFields
            ->filter(fn ($condition) => $condition->shouldInvalidateConditionals($data))
            ->map(fn ($condition) => $condition->nullData())
            ->collapse(1)
            ->toArray();
    }

    public function isMultiSelect(): bool
    {
        return count($this->multiSelectOptions) > 0;
    }

    /**
     * Gets the value of the field from saved data
     * @param array $data
     *
     * @return array
     */
    public function getFieldValueFromSavedData(array $data): array
    {
        $value = data_get($data, $this->name);
        if (is_null($value)) {
            return [
                $this->name =>
                $this->isMultiSelect() ? [] : ''
            ];
        }

        $field = [
            $this->name =>
            $this->isMultiSelect() ? explode('|',$value) : $value
        ];
        if (is_null($this->otherName)) {
            return $field;
        }

        $other = [ $this->otherName => data_get($data, $this->otherName)];
        return array_merge($field,$other);
    }

    public function viewFieldInfo(Collection $conditionalFieldNames)
    {
        return [
            'name' => $this->name,
            'prefix' => Str::beforeLast($this->name, '_') . '_',
            'number' => $this->guessNumberFromName(),
            'options' => $this->multiSelectOptions ?: $this->radioOptions,
            'required' => $this->isRequired(),
            'withOther' => ! is_null($this->otherName),
            'text' => $this->question,
            'isMain' => ! ($conditionalFieldNames->contains($this->name) || $this->optional)
        ];
    }

    public function inputName()
    {
        return $this->name . ($this->isMultiSelect() ? '[]' : '');
    }

    private function guessNumberFromName()
    {
        $array = explode('_', $this->name);
        $number = '';
        while (! is_numeric($number) && $array) {
            $number = array_pop($array);
        }
        return $array ? $number : Str::afterLast($this->name, '_');
    }

    private function getFieldValue(array $data): mixed
    {
        $value = data_get($data, $this->name);

        if (is_array($value)) {
            return implode('|', $value);
        }

        return $value;
    }

    private function getOtherValueArray(array $data): array
    {
        return $this->otherName
            ? [$this->otherName =>  data_get($data, $this->otherName)]
            : [];
    }

    private function multiSelectFieldNames(): array
    {
        return collect($this->multiSelectOptions)
            ->map(fn ($option) => $this->generateOptionName($option))
            ->toArray();
    }

    private function generateOptionName(string $option): string
    {
        return str_replace(' ', '_', "{$this->name}opt_{$option}");
    }

    /**
     *
     * @return array
     */
    private function expandMultiSelectData(array $data): array
    {
        $value = Arr::wrap(data_get($data, $this->name, []));
        return collect($this->multiSelectOptions)
            ->map(fn ($option) => [
                $this->generateOptionName($option) => in_array($option, $value ?? [])
            ])->collapse(1)->toArray();
    }
}
