<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{

    protected $fillable = ['JobRoleName','IsActive'];

    protected $table = 'jobroles';

    protected $connection = 'onboarding_connection';


}
