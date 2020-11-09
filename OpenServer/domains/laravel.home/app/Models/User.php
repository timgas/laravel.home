<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'id',
        'role',
        'email',
        'first_name',
        'last_name',
        'password',
        'country',
        'city',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];


   protected $appends = [
       'password'
    ];

    public function setRoleAttribute($value) {
        if (is_null($value)) {
            return  $this->attributes['role'] = 'worker';
        } else {
            return $this->attributes['role'] = $value;
        }
    }

    public function setPasswordAttribute($value)
    {
       $this->attributes['password'] = Hash::make($value);
    }

    public function organizations () {
        return $this->hasMany(Organization::class);
    }

    public function vacancies() {
        return $this->belongsToMany(Vacancy::class)
            ->withTimestamps();
    }
}
