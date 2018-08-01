<?php

use Illuminate\Http\Request;
use App\Http\Middleware\Cors;
use Illuminate\Http\Response;

class CorsMiddlewareTest extends TestCase
{
    /** @test **/
    public function it_can_set_correct_headers()
    {
        $request = Request::create('/', 'POST');

        $middleware = new Cors;

        $response = new Response;

        $response = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Methods'), 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type');
    }

    /** @test **/
    public function it_can_handle_prefilled_request()
    {
        $request = Request::create('/', 'OPTIONS');

        $middleware = new Cors;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Methods'), 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type');

        $this->assertEquals($response->getStatusCode(), 200);
    }
}
