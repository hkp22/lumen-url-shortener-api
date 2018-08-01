<?php

use App\Link;

class LinkStatsTest extends TestCase
{
    /** @test **/
    public function it_can_show_link_stats_by_shortened_code()
    {
        $link = factory(Link::class)->create([
            'requested_count' => 5,
            'used_count' => 234
        ]);

        $this->json('GET', '/stats', [
            'code' => $link->code
        ])
        ->seeJson($this->expectedJson($link));
    }

    /** @test **/
    public function link_stats_can_throw_404_if_link_not_found()
    {
        $this->json('GET', '/', ['code' => 'abc'])
            ->assertResponseStatus(404);
    }

    protected function expectedJson(Link $link)
    {
        return [
            'original_url' => $link->original_url,
            'short_url' => $link->shortUrl(),
            'code' => $link->code,
            'requested_count' => $link->requested_count,
            'used_count' => $link->used_count,
            'last_requested' => $link->last_requested->toDateTimeString(),
            'last_used' => $link->last_used ? $link->last_used->toDateTimeString() : null,
        ];
    }
}
