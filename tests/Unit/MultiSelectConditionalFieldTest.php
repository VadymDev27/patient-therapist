<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ScreeningSurvey;
use Illuminate\Support\Arr;
use Surveys\Field;
use Surveys\Testing\FieldsExample;
use Surveys\Testing\MultiSelectConditionalExample;
use Surveys\Testing\MultiSelectFieldsExample;

class MultiSelectConditionalFieldTest extends TestCase
{

    private MultiSelectConditionalExample $example;

    private function example()
    {
        if (! isset($this->example)) {
            $this->example = new MultiSelectConditionalExample();
        }

        return $this->example;
    }

    public function test_correctly_sets_names()
    {
        $expect = [
            'test', 'testopt_Option1', 'testopt_Option_2', 'testopt_Option3', 'testopt_Option4', 'test2', 'test3', 'test4', 'test5'
        ];

        $this->assertEquals($expect, $this->example()->fieldNames());
    }

    public function test_expands_multi_select_values()
    {
        $data = [
            'test' => ['Option1', 'Option4']
        ];

        $expect = [
            'test' => "Option1|Option4",
            'testopt_Option1' => true,
            'testopt_Option_2' => false,
            'testopt_Option3' => false,
            'testopt_Option4' => true,
            'test2' => null,
            'test3' => null,
            'test4' => null,
            'test5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_nulls_dependent_fields()
    {
        $data = [
            'test' => ['Option1', 'Option4'],
            'test4' => 'hello'
        ];

        $expect = [
            'test' => "Option1|Option4",
            'testopt_Option1' => true,
            'testopt_Option_2' => false,
            'testopt_Option3' => false,
            'testopt_Option4' => true,
            'test2' => null,
            'test3' => null,
            'test4' => null,
            'test5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_nulls_dependent_multiselect_fields()
    {
        $data = [
            'test' => ['Option1', 'Option4'],
            'test2' => 'hello'
        ];

        $expect = [
            'test' => "Option1|Option4",
            'testopt_Option1' => true,
            'testopt_Option_2' => false,
            'testopt_Option3' => false,
            'testopt_Option4' => true,
            'test2' => null,
            'test3' => null,
            'test4' => null,
            'test5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_does_not_null_when_condition_met()
    {
        $data = [
            'test' => ['Option1', 'Option3'],
            'test2' => 'hello'
        ];

        $expect = [
            'test' => "Option1|Option3",
            'testopt_Option1' => true,
            'testopt_Option_2' => false,
            'testopt_Option3' => true,
            'testopt_Option4' => false,
            'test2' => 'hello',
            'test3' => null,
            'test4' => null,
            'test5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_gives_correct_main_field_names()
    {
        $expect = ['test[]'];

        $this->assertEquals($expect, $this->example()->mainFieldNames()->toArray());
    }
}
