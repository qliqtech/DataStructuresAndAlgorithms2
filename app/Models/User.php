<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'userroleid',
        'created_by',
        'Isconfirmed',
        'ConfirmedOn',
        'phonenumber',
        'IsDeleted',
        'IsDeletedOn',
        'IsActive',
        'DeletedBy',
        'DeactivatedOn',
        'confirmationcode',
        'employerid' ,
        'classid',
        'requirespasswordreset',
        'jobroleid',
        'isverifiedasuseragent',
        'nsssyncapikey',
        'jobrolename',
        'onboardingcompanystate',
        'front_portrait_url',
        'nssnumber',
        'useraccountstatus'
    ];

    protected $connection = 'onboarding_connection';




    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}
