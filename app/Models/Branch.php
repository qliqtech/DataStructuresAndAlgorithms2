<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['branchname','branchlocation','companyid','created_by','branchregionname',
        'IsDeleted','IsActive','DeactivatedOn','ghanapostaddress','DeactivatedOn','regionid','city','address','isheadquarters'
        ];


    protected $connection = 'onboarding_connection';

}
