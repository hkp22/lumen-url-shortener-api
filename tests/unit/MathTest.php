<?php

class MathTest extends TestCase
{
    protected $mappings = [
        1 => 1,
        100 => '1C',
        1000 => 'g8',
        100000 => 'q0U',
        99999999 => '6LAzd',
        99999999999 => '1L9zO9N'
    ];

    /** @test **/
    public function check_correct_encode_of_given_value()
    {
        $math = new \App\Helpers\Math;

        foreach ($this->mappings as $value => $encoded) {
            $this->assertEquals($encoded, $math->toBase($value));
        }
    }
}
