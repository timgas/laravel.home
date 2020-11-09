<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'organization_id',
        'vacancy_name',
        'status',
        'workers_amount',
        'workers_booked',
        'salary'
    ];

    protected $hidden = [
//      'organization_id',
        'deleted_at'
    ];

    public function setWorkers_BookedAttributes($value)
    {
        if (is_null($value)) {
            $this->attributes['workers_booked'] = 0;
        } else {
            return $this->attributes['workers_booked'] = $value;
        }
    }



    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();;
    }
}
