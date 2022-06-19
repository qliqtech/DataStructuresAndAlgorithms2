<?php

namespace Tests\Unit\ImplementationService;

use App\ImplementationService\CountryCurrencyImplementationService;
use Tests\TestCase;

/**
 * Class CountryCurrencyImplementationServiceTest.
 *
 * @covers \App\ImplementationService\CountryCurrencyImplementationService
 */
final class CountryCurrencyImplementationServiceTest extends TestCase
{
    private CountryCurrencyImplementationService $countryCurrencyImplementationService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->countryCurrencyImplementationService = new CountryCurrencyImplementationService();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->countryCurrencyImplementationService);
    }

    public function testListCountriesAndCurrenciesImplementation(): void
    {
        $parameters["currency_iso_code"] = "AB";

        $parameters["currency_iso_numeric_code"] = "A";

        $parameters["currency_common_name"] = "Cedi";

        $parameters["currency_official_name"] = "Ghanaian Cedi";

        $parameters["currency_symbol"] = "AC";

        $parameters["country_currency_code"] = "AD";

        $parameters["country_iso3_code"] = "ABB";

        $parameters["country_common_name"] = "VB";


        $parameters["country_continent_code"] = "ABC";


        $parameters["number_of_rows_per_page"] = 2;

        $parameters["current_page_number"] = 1;



        $parameters["some_value"] = null;


           $response = $this->countryCurrencyImplementationService->listCountriesAndCurrenciesImplementation($parameters);

           $this->assertIsArray($response);
    }

    public function testUploadCurrencyIntoDb(): void
    {

       $result = $this->countryCurrencyImplementationService->uploadCurrencyIntoDb();

       $this->assertTrue($result);


    }

    public function testUploadCountryIntoDb(): void
    {
        $result = $this->countryCurrencyImplementationService->uploadCountryIntoDb();

        $this->assertTrue($result);






    }
}
