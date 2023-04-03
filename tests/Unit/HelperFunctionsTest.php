<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ScreeningSurvey;

class HelperFunctionsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_append_string_function()
    {
        $array = ['p1s','p2s','p3s','p4s'];
        $calculatedArray = appendStringToEach(range(1,4),'p','s');
        $this->assertTrue($array === $calculatedArray);
    }

}
