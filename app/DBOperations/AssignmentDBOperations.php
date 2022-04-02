<?php

namespace App\DBOperations;

use App\Models\Assignment_students;
use Illuminate\Database\Eloquent\Model;

class AssignmentDBOperations extends BaseDBOperations
{
    protected $model;

    public function createstudentandassignment($keys): ?Model
    {

       // echo 'here';die();//check

        return Assignment_students::create($keys);
    }

}
