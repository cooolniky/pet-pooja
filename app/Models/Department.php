<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $table = 'department';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'created_by', 'last_modified_by','created_date','last_modified_date'
    ];

    public function EmployeeWithHighestSalary()
    {
        return $this->hasMany('App\Models\Employee', 'department_id', 'id')->where('status',1)->orderBy('salary','desc')->limit(1);
    }

    public function YoungestEmployee()
    {
        return $this->hasMany('App\Models\Employee', 'department_id', 'id')->where('status',1)->orderBy('dob','desc')->limit(1);
    }
}
