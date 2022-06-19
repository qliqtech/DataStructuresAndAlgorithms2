<?php

namespace App\ImplementationService;

use App\Models\Audit;

/**
 * Handling Audit Activities
 *
 */

class AuditService
{

    /**
     *capturing audit request and responses
     *
     * @param array $params
     *
     * @return bool
     */

    public function saveAudit(array$params){


        Audit::create($params);

        return true;
    }
}
