<?php

use Illuminate\Http\Request;
use App\Http\Middleware\ModifiesUrlRequestData;

class UrlMiddlewareTest extends TestCase
{
    /** @test **/
    public function it_can_prepend_http_to_url()
    {
        $request = new Request;

        $request->replace([
            'url' => 'google.com'
        ]);

        $middleware = new ModifiesUrlRequestData;

        $middleware->handle($request, function ($req) {
            $this->assertEquals('http://google.com', $req->url);
        });
    }

    /** @test **/
    public function it_cannot_prepend_http_to_url_if_scheme_exists()
    {
        $request = new Request;

        $urls = [
            'ftp://www.example.com',
            'http://www.example.com',
            'https://www.example.com'
        ];

        foreach ($urls as $url) {
            $request->replace([
                'url' => $url
            ]);

            $middleware = new ModifiesUrlRequestData;

            $middleware->handle($request, function ($req) use ($url) {
                $this->assertEquals($url, $req->url);
            });
        }
    }
}
