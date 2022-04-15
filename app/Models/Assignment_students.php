<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment_students extends Model
{

    protected $fillable = ['assignmentid','userid','issubmitted','ismarked','markedon','comment','created_at','updated_at','markedby','classid','submittedon','answerurl'];


    protected $connection = 'onboarding_connection';

}
