<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{


    protected $fillable = ['realmname'];

    protected $connection = 'onboarding_connection';


}
