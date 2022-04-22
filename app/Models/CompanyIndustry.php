<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyIndustry extends Model
{

    protected $fillable = ['industryname'];

    protected $connection = 'onboarding_connection';


}
