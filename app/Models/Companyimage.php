<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companyimage extends Model
{


    protected $fillable = ['imageurl','companyid','IsDeleted','created_by'];

    protected $connection = 'onboarding_connection';


}
