<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'city', 'country', 'user_id'
    ];

    protected $hidden = [
       'user_id', 'deleted_at'
    ];
 /*   // Нужно для ацесоров
    protected $appends = [
      'organizations'
    ];*/

/*       //Accessors
       public function getOrganizationsAttribute()
       {

       }*/
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }
}
