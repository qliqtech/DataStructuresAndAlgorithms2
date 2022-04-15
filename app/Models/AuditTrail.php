<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AuditTrail extends Model
{

    protected $fillable = ['userid','actionid','activityname','browser','userip','requesturl','clientserverip','responsemessage',
        'requestbody','responsebody'];

    protected $connection = 'onboarding_connection';


}
