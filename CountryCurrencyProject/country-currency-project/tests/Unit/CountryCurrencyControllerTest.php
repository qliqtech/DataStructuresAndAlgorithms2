<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\CountryCurrencyController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Class CountryCurrencyControllerTest.
 *
 * @covers \App\Http\Controllers\CountryCurrencyController
 */
final class CountryCurrencyControllerTest extends TestCase
{
    private CountryCurrencyController $countryCurrencyController;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->countryCurrencyController = new CountryCurrencyController();
        $this->app->instance(CountryCurrencyController::class, $this->countryCurrencyController);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->countryCurrencyController);
    }

    public function testSearchCountryOrCurrency(): void
    {
        //   $this->get('/path')
        //       ->assertStatus(200);

        $request = new Request();

        $checkfornorequestparams = $this->countryCurrencyController->searchCountryOrCurrency($request)->status();

        $this->assertEquals(200,$checkfornorequestparams);

        $this->get('/api/country_currency/search_country_or_currency?country_continent_code=TESTLONGVALUES')
            ->assertStatus(400);



    }

}
