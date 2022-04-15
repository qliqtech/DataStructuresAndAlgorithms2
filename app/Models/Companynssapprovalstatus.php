<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companynssapprovalstatus extends Model
{


    protected $fillable = ['statusname'];


    protected $connection = 'onboarding_connection';

}
