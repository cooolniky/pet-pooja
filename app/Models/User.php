<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'joining_date', 'role_id', 'landline_number',
        'mobile_number', 'status', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    public function ParentUser()
    {
        return $this->belongsTo('App\Models\User', 'parent_id');
    }

    public function UserPermission()
    {
        return $this->hasManyThrough(
            'App\Models\RolePermission', 'App\Models\Role',
            'id', 'role_id', 'role_id'
        );
    }
    
    /**
     * update User's Token
     *
     * @param array $models
     * @return mixed
     */
    public function updateUserTokens(array $models = [])
    {
        $user = User::find(Auth::id());
        $user->device_token = $models['device_token'];
        $user->device_type = $models['device_type'];
        $user->api_token = str_random(60);
        $user_id = $user->save();
        if($user_id){
            return $user;
        }
        return false;
    }
}
