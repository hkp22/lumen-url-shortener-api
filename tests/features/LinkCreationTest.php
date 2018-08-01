<?php

use App\Link;
use Carbon\Carbon;

class LinkCreationTest extends TestCase
{
    /** @test **/
    public function it_fails_if_no_url_given()
    {
        $response = $this->json('POST', '/')
                        ->seeJson(['url' => ['Please enter a valid URL.']])
                        ->notSeeInDatabase('links', [
                            'code' => 1
                        ])
                        ->assertResponseStatus(422);
    }

    /** @test **/
    public function it_fails_if_url_is_invalid()
    {
        $response = $this->json('POST', '/', [
                            'url' => 'http://google^&$$'
                        ])
                        ->seeJson(['url' => ['Please enter a valid URL.']])
                        ->notSeeInDatabase('links', [
                            'code' => 1
                        ])
                        ->assertResponseStatus(422);
    }

    /** @test **/
    public function it_can_create_shortened_url()
    {
        $response = $this->json('POST', '/', [
            'url' => 'http://www.google.com'
        ])
        ->seeInDatabase('links', [
            'original_url' => 'http://www.google.com',
            'code' => 1
        ])
        ->seeJson([
            'data' => [
                'original_url' => 'http://www.google.com',
                'short_url' => env('CLIENT_URL') . '/1',
                'code' => '1'
            ]
        ])
        ->assertResponseStatus(201);
    }

    /** @test **/
    public function it_can_shortened_url_only_once()
    {
        $url = 'http://www.google.com';
        $this->json('POST', '/', ['url' => $url]);
        $this->json('POST', '/', ['url' => $url]);

        $links = Link::where('original_url', $url)->get();

        $this->assertCount(1, $links);
    }

    /** @test **/
    public function it_can_increment_requested_count()
    {
        $url = 'http://www.google.com';
        $this->json('POST', '/', ['url' => $url]);
        $this->json('POST', '/', ['url' => $url]);

        $this->seeInDatabase('links', [
            'original_url' => $url,
            'requested_count' => 2
        ]);
    }

    /** @test **/
    public function it_can_update_last_requested_date_for_existing_link()
    {
        Link::flushEventListeners();

        $link = factory(Link::class)->create([
            'last_requested' => Carbon::now()->subDays(2)
        ]);

        $this->json('POST', '/', ['url' => $link->original_url])
            ->seeInDatabase('links', [
                'original_url' => $link->original_url,
                'last_requested' => Carbon::now()->toDateTimeString()
            ]);
    }
}
