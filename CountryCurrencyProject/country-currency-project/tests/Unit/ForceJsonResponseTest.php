<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class ForceJsonResponseTest.
 *
 * @covers \App\Http\Middleware\ForceJsonResponse
 */
final class ForceJsonResponseTest extends TestCase
{
    private ForceJsonResponse $forceJsonResponse;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->forceJsonResponse = new ForceJsonResponse();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->forceJsonResponse);
    }

    public function testHandle(): void
    {

        $response = \Mockery::Mock(Response::class)
            ->shouldReceive('header')
            ->with('Accept', 'application/json')
            ->shouldReceive('header')
            ->getMock();


        $request = Request::create('/country_currency/search_country_or_currency', 'GET');



        $this->forceJsonResponse->handle($request, function () use ($response){
            return $response;
        });

    }
}
