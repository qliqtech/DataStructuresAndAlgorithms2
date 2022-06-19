<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{

    protected $fillable = [
        'iso_code', 'iso_numeric_code', 'official_name','symbol','common_name'
    ];



}
