<?php

use App\Link;
use Carbon\Carbon;

class LinkShowTest extends TestCase
{
    /** @test **/
    public function it_can_show_link_details()
    {
        $link = factory(Link::class)->create();

        $response = $this->json('GET', '/', [
            'code' => $link->code
        ])
        ->seeJson([
            'data' => [
                'original_url' => $link->original_url,
                'short_url' => $link->shortUrl(),
                'code' => $link->code
            ]
        ])
        ->assertResponseStatus(200);
    }

    /** @test **/
    public function it_can_throw_404_if_link_not_found()
    {
        $response = $this->json('GET', '/', [
                'code' => 'abc'
            ])
            ->assertResponseStatus(404);

        $this->assertEmpty($this->response->getContent());
    }

    /** @test **/
    public function it_can_increment_used_count()
    {
        $link = factory(Link::class)->create();

        $response = $this->json('GET', '/', ['code' => $link->code]);
        $response = $this->json('GET', '/', ['code' => $link->code]);
        $response = $this->json('GET', '/', ['code' => $link->code]);

        $this->seeInDatabase('links', [
            'original_url' => $link->original_url,
            'used_count' => 3
        ]);
    }

    /** @test **/
    public function it_can_update_last_used_date()
    {
        Link::flushEventListeners();

        $link = factory(Link::class)->create([
            'last_used' => Carbon::now()->subDays(2)
        ]);

        $this->json('GET', '/', ['code' => $link->code])
            ->seeInDatabase('links', [
                'original_url' => $link->original_url,
                'last_used' => Carbon::now()->toDateTimeString()
            ]);
    }
}
