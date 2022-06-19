<?php

namespace Tests\Unit\ImplementationService;

use App\ImplementationService\AuditService;
use Psy\Util\Str;
use Tests\TestCase;

/**
 * Class AuditServiceTest.
 *
 * @covers \App\ImplementationService\AuditService
 */
final class AuditServiceTest extends TestCase
{
    private AuditService $auditService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->auditService = new AuditService();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->auditService);
    }

    public function testSaveAudit(): void
    {
        /** @todo This test is incomplete. */


        $useragent = ['client_ip'=>'12.34.56.90',
            'guid'=>"testguid1234",
            'request_url'=>"sampleurl",
            'browser'=>"sample borwser details",
            'response_body'=>"sample response body"];


       $result = $this->auditService->saveAudit($useragent);

        $this->assertTrue($result);
    }
}
