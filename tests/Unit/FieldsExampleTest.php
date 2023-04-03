<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ScreeningSurvey;
use Illuminate\Support\Arr;
use Surveys\Field;
use Surveys\Testing\ConditionalFieldsExample;
use Surveys\Testing\FieldsExample;

class FieldsExampleTest extends TestCase
{

    private ConditionalFieldsExample $example;

    private function example()
    {
        if (! isset($this->example)) {
            $this->example = new ConditionalFieldsExample();
        }

        return $this->example;
    }

    public function test_correctly_nulls_dependendent_values()
    {
        $data = [
            'test' => 'No',
            'test2' => 'whatever',
            'test3' => 'Yes',
            'test4' => 'Hello',
            'test5' => 'World'
        ];

        $expect = [
            'test' => 'No',
            'test2' => null,
            'test3' => 'Yes',
            'test4' => 'Hello',
            'test5' => 'World'
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_correctly_nulls_both_fields_dependendent_values()
    {
        $data = [
            'test' => 'No',
            'test2' => 'whatever',
            'test3' => 'No',
            'test4' => 'Hello',
            'test5' => 'World'
        ];

        $expect = [
            'test' => 'No',
            'test2' => null,
            'test3' => 'No',
            'test4' => null,
            'test5' => null
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }

    public function test_correctly_leaves_data_when_condition_met()
    {
        $data = [
            'test' => 'Yes',
            'test2' => 'whatever',
            'test3' => 'Yes',
            'test4' => 'Hello',
            'test5' => 'World'
        ];

        $this->assertEquals($data, $this->example()->processData($data));
    }

    public function test_excludes_additional_fields()
    {
        $data = [
            'test' => 'Yes',
            'test2' => 'whatever',
            'test3' => 'Yes',
            'test4' => 'Hello',
            'test5' => 'World',
            'test6' => 'oops'
        ];

        $expect = [
            'test' => 'Yes',
            'test2' => 'whatever',
            'test3' => 'Yes',
            'test4' => 'Hello',
            'test5' => 'World'
        ];

        $this->assertEquals($expect, $this->example()->processData($data));
    }
}
