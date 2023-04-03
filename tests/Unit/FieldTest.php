<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ScreeningSurvey;
use Illuminate\Support\Arr;
use Surveys\Field;

class FieldTest extends TestCase
{
    private array $testData = [
        'test' => 'Yes',
        'test2' => 'hello',
        'test3' => 'world'
    ];

    private array $testData2 = [
        'test' => 'Maybe',
        'test2' => 'hello',
        'test3' => 'world'
    ];

    private array $testData3 = [
        'test' => 'No',
        'test2' => 'hello',
        'test3' => 'world',
        'test4' => 'superstar'
    ];

    public function test_correctly_nulls_dependendent_values()
    {
        $field = Field::make('test')->conditionalFields(['test2'],['Yes', 'Maybe']);

        $result = $field->invalidateConditionalFields($this->testData3);

        $this->assertEquals(Arr::set($this->testData3, 'test2', null), $result);
    }

    public function test_correctly_nulls_multiple_dependendent_values()
    {
        $field = Field::make('test')->conditionalFields(['test2'],['Yes', 'Maybe'])->conditionalFields(['test4'], 'Maybe');

        $result = $field->invalidateConditionalFields($this->testData3);

        $expect = [
            'test' => 'No',
            'test2' => null,
            'test3' => 'world',
            'test4' => null
        ];
        $this->assertEquals($expect, $result);
    }

    public function test_generates_correct_nulled_fields()
    {
        $field = Field::make('test')->conditionalFields(['test2'],['Yes', 'Maybe']);

        $expect = ['test2' => null];
        $this->assertEquals($expect, $field->getNulledFields($this->testData3));
    }

    public function test_correctly_does_not_null_dependendent_values_when_condition_met()
    {
        $field = Field::make('test')->conditionalFields(['test2'],['Yes', 'Maybe']);

        $result = $field->invalidateConditionalFields($this->testData2);

        $this->assertEquals($this->testData2, $result);
    }

    public function test_correctly_does_not_null_dependendent_values_when_no_conditionals()
    {
        $field = Field::make('test');

        $result = $field->invalidateConditionalFields($this->testData2);

        $this->assertEquals($this->testData2, $result);
    }

    public function test_correctly_returns_multi_select_fieldnames()
    {
        $field = Field::make('test')->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4']);

        $expect = [
            'test', 'testopt_Option1', 'testopt_Option_2', 'testopt_Option3', 'testopt_Option4'
        ];
        $this->assertEquals($expect, $field->names());
    }

    public function test_correctly_expands_multi_select_data()
    {
        $field = Field::make('test')->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4']);

        $value = ['test' => ['Option 2', 'Option4']];
        $expect = [
            'test' => "Option 2|Option4",
            'testopt_Option1' => false,
            'testopt_Option_2' => true,
            'testopt_Option3' => false,
            'testopt_Option4' => true
        ];
        $this->assertEquals($expect, $field->getFieldData($value));
    }

    public function test_correctly_nulls_multi_select_with_multiple_conditionals()
    {
        $field = Field::make('test')
            ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4'])
            ->conditionalFields(['test2'],['Option 2', 'Option3'])
            ->conditionalFields(['test4'], 'Option4');

        $data = [
            'test' => ['Option1'],
            'test2' => 'hello',
            'test3' => 'world',
            'test4' => 'superstar'
        ];
        $expect = [
            'test2' => null,
            'test4' => null
        ];
        $this->assertEquals($expect, $field->getNulledFields($data));
    }

    public function test_correctly_nulls_multi_select_with_multiple_conditionals_when_only_one_true()
    {
        $field = Field::make('test')
            ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4'])
            ->conditionalFields(['test2'],['Option 2', 'Option3'])
            ->conditionalFields(['test4'], 'Option4');

        $data = [
            'test' => ['Option1', 'Option 2'],
            'test2' => 'hello',
            'test3' => 'world',
            'test4' => 'superstar'
        ];
        $expect = [
            'test4' => null,
        ];
        $this->assertEquals($expect, $field->getNulledFields($data));
    }

    public function test_empty_field_is_null()
    {
        $field = Field::make('test')->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4']);

        $value = ['test2' => ['Option 2', 'Option4']];
        $expect = [
            'test' => null,
            'testopt_Option1' => false,
            'testopt_Option_2' => false,
            'testopt_Option3' => false,
            'testopt_Option4' => false
        ];

        $this->assertEquals($expect, $field->getFieldData($value));
    }

    public function test_names_are_correct_for_withOther()
    {
        $field = Field::make('test')
            ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
            ->withHiddenOther();

        $expect = [
            'test', 'testopt_Option1', 'testopt_Option_2', 'testopt_Option3', 'testopt_Option4', 'testopt_Other', 'test_Other'
        ];
        $this->assertEquals($expect, $field->names());
    }

    public function test_correctly_nulls_other()
    {
        $field = Field::make('test')
        ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
        ->withHiddenOther();

        $data = [
            'test' => ['Option 2', 'Option4'],
            'test_Other' => 'some random stuff'
        ];
        $expect = [
            'test' => ['Option 2', 'Option4'],
            'test_Other' => null
        ];
        $this->assertEquals($expect, $field->invalidateConditionalFields($data));
    }

    public function test_correctly_gets_field_values_for_other()
    {
        $field = Field::make('test')
        ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
        ->withHiddenOther();

        $data = [
            'test' => ['Option 2', 'Option4'],
            'test_Other' => null
        ];
        $expect = [
            'test' => "Option 2|Option4",
            'testopt_Option1' => false,
            'testopt_Option_2' => true,
            'testopt_Option3' => false,
            'testopt_Option4' => true,
            'testopt_Other' => false,
            'test_Other' => null
        ];
        $this->assertEquals($expect, $field->getFieldData($data));
    }

    public function test_does_not_null_if_condition_met()
    {
        $field = Field::make('test')
        ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
        ->withHiddenOther();

        $data = [
            'test' => ['Option 2', 'Option4', 'Other'],
            'test_Other' => 'some random stuff'
        ];
        $expect = [
            'test' => ['Option 2', 'Option4', 'Other'],
            'test_Other' => 'some random stuff'
        ];
        $this->assertEquals($expect, $field->invalidateConditionalFields($data));
    }

    public function test_gets_value_for_other_when_not_null()
    {
        $field = Field::make('test')
        ->multiSelect(['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
        ->withHiddenOther();

        $data = [
            'test' => ['Option 2', 'Option4', 'Other'],
            'test_Other' => 'some random stuff'
        ];
        $expect = [
            'test' => "Option 2|Option4|Other",
            'testopt_Option1' => false,
            'testopt_Option_2' => true,
            'testopt_Option3' => false,
            'testopt_Option4' => true,
            'testopt_Other' => true,
            'test_Other' => 'some random stuff'
        ];
        $this->assertEquals($expect, $field->getFieldData($data));
    }

    public function test_generates_reasonable_fake_data()
    {
        $field = Field::make('test')
                    ->multiSelect($options = ['Option1', 'Option 2', 'Option3', 'Option4', 'Other'])
                    ->withHiddenOther();

        $data = $field->fakeData();

        foreach (data_get($data,'test',[]) as $selected) {
            $this->assertContains($selected, $options);
        }

    }

    public function test_gives_correct_dependent_field_names()
    {
        $field = Field::make('test')
                ->conditionalFields(['test3'],'Some value')
                ->conditionalFields(['test4','test5'], ['Another value', 'Yet another']);

        $expect = ['test3','test4','test5'];

        $this->assertEquals($expect, $field->conditionalFieldNames());
    }
}
