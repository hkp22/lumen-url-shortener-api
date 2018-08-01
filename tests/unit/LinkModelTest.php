<?php

use App\Link;

class LinkModelTest extends TestCase
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
    public function check_correct_code_is_generated()
    {
        $link = new Link;

        foreach ($this->mappings as $id => $expectedCode) {
            $link->id = $id;
            $this->assertEquals($link->getCode(), $expectedCode);
        }
    }

    /** @test **/
    public function exception_is_thrown_with_no_id()
    {
        $this->expectException(\App\Exceptions\CodeGenerationException::class);

        $link = new Link;
        $link->getCode();
    }

    /** @test **/
    public function it_can_get_model_using_byCode_method()
    {
        $link = factory(Link::class)->create([
            'code' => 'abc'
        ]);

        $model = $link->byCode($link->code)->first();

        $this->assertInstanceOf(Link::class, $model);
        $this->assertEquals($model->original_url, $link->original_url);
    }

    /** @test **/
    public function it_can_get_short_url_from_link_model()
    {
        $link = factory(Link::class)->create(['code' => 'abc']);

        $this->assertEquals($link->shortUrl(), env('CLIENT_URL') . '/' . $link->code);
    }

    /** @test **/
    public function it_can_return_null_short_url_if_has_no_code()
    {
        Link::flushEventListeners();

        $link = factory(Link::class)->create();

        $this->assertNull($link->shortUrl());
    }
}
