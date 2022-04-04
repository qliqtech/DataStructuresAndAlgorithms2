<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{

    protected $fillable = ['userrolename','slug','description','status','permissions','created_by','is_system_generated','type','realmid','permissions'];

}
