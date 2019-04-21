<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $table = 'employee';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id',
        'name',
        'dob',
        'phone',
        'photo',
        'email',
        'salary',
        'status',
        'created_by',
        'last_modified_by',
        'created_date',
        'last_modified_date'
    ];

    /**
     * Get the Date of birth format.
     *
     * @param  string  $value
     * @return string
     */
    public function getDobAttribute($value)
    {
        return date('d-M-Y',strtotime($value));
    }

    /**
     * Get the Date of birth format.
     *
     * @param  string  $value
     * @return string
     */
    public function getSalaryAttribute($value)
    {
        return $value + 0;
    }

    public function Department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }
}
