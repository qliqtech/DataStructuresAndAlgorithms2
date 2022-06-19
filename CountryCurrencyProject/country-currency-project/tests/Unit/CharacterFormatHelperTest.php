<?php

namespace Tests\Unit\Helper;

use App\Helper\CharacterFormatHelper;
use Tests\TestCase;

/**
 * Class CharacterFormatHelperTest.
 *
 * @covers \App\Helper\CharacterFormatHelper
 */
final class CharacterFormatHelperTest extends TestCase
{
    private CharacterFormatHelper $characterFormatHelper;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();


        $this->characterFormatHelper = new CharacterFormatHelper();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->characterFormatHelper);
    }

    public function testCsvToArray()
    {

      $result =  $this->characterFormatHelper::csvToArray('C:\CountryCurrencyProject\country-currency-project\storage\app\01-Currencies.csv',',');



        $this->assertIsArray($result);
    }


}
