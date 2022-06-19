<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
use Closure;
use Tests\TestCase;
use Illuminate\Http\Response;


/**
 * Class CorsTest.
 *
 * @covers \App\Http\Middleware\Cors
 */
final class CorsTest extends TestCase
{
    private Cors $cors;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->cors = new Cors();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->cors);
    }

    public function testHandle()
    {


        $response = \Mockery::Mock(Response::class)
            ->shouldReceive('header')
            ->with('Access-Control-Allow-Origin', '*')
            ->shouldReceive('header')
            ->with('Access-Control-Allow-Methods', 'HEAD, GET, PUT, PATCH, POST')
            ->getMock();


        $request = Request::create('/api/country_currency/search_country_or_currency', 'GET');



        $this->cors->handle($request, function () use ($response){
            return $response;
        });


    }
}
