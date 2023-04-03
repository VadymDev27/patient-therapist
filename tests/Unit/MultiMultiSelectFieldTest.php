<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ScreeningSurvey;
use Illuminate\Support\Arr;
use Surveys\Field;
use Surveys\Testing\FieldsExample;
use Surveys\Testing\MultiMultiSelectExample;
use Surveys\Testing\MultiSelectConditionalExample;
use Surveys\Testing\MultiSelectFieldsExample;

class MultiMultiSelectFieldTest extends TestCase
{

    private MultiMultiSelectExample $example;

    private function example()
    {
        if (! isset($this->example)) {
            $this->example = new MultiMultiSelectExample();
        }

        return $this->example;
    }

    public function test_correctly_sets_names()
    {
        $expect = [
            'test_1', 'test_1opt_Option1', 'test_1opt_Option_2', 'test_1opt_Option3', 'test_1opt_Option4', 'test_2', 'test_3', 'test_4', 'test_4opt_Option_1', 'test_4opt_Option_2', 'test_5'
        ];

        $this->assertEquals($expect, $this->example()->fieldNames());
    }

    public function test_nulls_dependent_multiselects()
    {
        $data = [
            'test_1' => ['Option1', 'Option 2'],
            'test_2' => 'world',
            'test_3' => 'No',
            'test_4' => ['Option 1', 'Option 2']
        ];

        $expect = [
            'test_1' => "Option1|Option 2",
            'test_1opt_Option1' => true,
            'test_1opt_Option_2' => true,
            'test_1opt_Option3' => false,
            'test_1opt_Option4' => false,
            'test_2' => 'world',
            'test_3' => 'No',
            'test_4' => null,
            'test_4opt_Option_1' => null,
            'test_4opt_Option_2' => null,
            'test_5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_correctly_gets_data_from_saved_data()
    {

        $data = [
            'test_1' => "Option1|Option 2",
            'test_1opt_Option1' => true,
            'test_1opt_Option_2' => true,
            'test_1opt_Option3' => false,
            'test_1opt_Option4' => false,
            'test_2' => 'world',
            'test_3' => 'No',
            'test_4' => "Option 1",
            'test_4opt_Option_1' => true,
            'test_4opt_Option_2' => false,
            'test_5' => null,
            'test_7' => 'extra field to ignore'
        ];

        $expect = [
            'test_1' => ['Option1', 'Option 2'],
            'test_2' => 'world',
            'test_3' => 'No',
            'test_4' => ['Option 1'],
            'test_5' => null
        ];

        $this->assertEquals($expect, $this->example()->getViewData($data));
    }

    public function test_view_field_info_is_correct()
    {
        $expect = [
            [
                'name' => 'test_1',
                'prefix' => 'test_',
                'number' => '1',
                'options' => ['Option1', 'Option 2', 'Option3', 'Option4'],
                'required' => true,
                'withOther' => false,
                'text' => '',
                'isMain' => true
            ],
            [
                'name' => 'test_2',
                'prefix' => 'test_',
                'number' => '2',
                'options' => [],
                'required' => false,
                'withOther' => false,
                'text' => '',
                'isMain' => false
            ],
            [
                'name' => 'test_3',
                'prefix' => 'test_',
                'number' => '3',
                'options' => [],
                'required' => false,
                'withOther' => false,
                'text' => '',
                'isMain' => true
            ],
            [
                'name' => 'test_4',
                'prefix' => 'test_',
                'number' => '4',
                'options' => ['Option 1', 'Option 2'],
                'required' => false,
                'withOther' => false,
                'text' => '',
                'isMain' => false
            ],
            [
                'name' => 'test_5',
                'prefix' => 'test_',
                'number' => '5',
                'options' => [],
                'required' => true,
                'withOther' => false,
                'text' => '',
                'isMain' => false
            ]
        ];

        $this->assertEquals($expect, $this->example()->getViewFieldInfo());
    }

    public function test_correctly_returns_required_field_names()
    {
        $expect = ['test_1[]', 'test_5'];

        $this->assertEquals($expect, $this->example()->requiredFieldNames());
    }

    public function test_fake_data_contains_correct_options()
    {
        $data = $this->example()->generateFakeData();

        foreach (explode('|',data_get($data,'test_1',[])) as $selected) {
            $this->assertContains($selected, ['Option1', 'Option 2', 'Option3', 'Option4']);
        }
    }

    public function test_gives_correct_main_field_names()
    {
        $expect = ['test_1[]','test_3'];

        $this->assertEquals($expect, $this->example()->mainFieldNames()->toArray());
    }
}
