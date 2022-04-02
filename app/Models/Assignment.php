<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{

    protected $fillable = ['title','task','created_by','IsDeleted','classid'];

}
