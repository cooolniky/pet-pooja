<?php namespace App\Repositories\Eloquent;

use App\Repositories\Contract\UserInterface;
use App\Models\User;
use Auth;
use App\Traits\CommonModelTrait;


/**
 * Class UserRepository
 *
 * @package App\Repositories\Eloquent
 */
class UserRepository implements UserInterface
{

    use CommonModelTrait;
    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {
        return User::with('Role', 'ParentUser')->get();
    }

    /**
     * Get all User with role and ParentUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return User::with('Role', 'ParentUser');
    }

    /**
     * use for sorting
     *
     * @return array
     */
    public function getSortFields($index)
    {
        $sortableFields = [
            "first_name",
            "last_name",
            "",
            "email",
            "status"
        ];

        return $sortableFields[ $index ];
    }

    /**
     * get User By fieldname getUserByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getUserByField($id, $field_name)
    {
        return User::where($field_name, $id)->first();
    }

    /**
     * Add & update User addUser
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addUser($models)
    {
        if (isset($models['id'])) {
            $user = User::find($models['id']);
        } else {
            $user = new User;
            $user->created_at = date('Y-m-d H:i:s');
            $user->created_by = Auth::user()->id;
            $user->password = bcrypt($models['password']);
            $user->email = $models['email'];
        }

        $user->name = $models['first_name'] . " " . $models['last_name'];
        $user->first_name = $models['first_name'];
        $user->last_name = $models['last_name'];
        $user->role_id = $models['role_id'];
        if (isset($models['status'])) {
            $user->status = $models['status'];
        } else {
            $user->status = 0;
        }

        $user->updated_by = Auth::user()->id;
        $user->updated_at = date('Y-m-d H:i:s');
        $userId = $user->save();

        if ($userId) {
            if (!isset($models['id'])) {
                $user->password = $models['password'];
            }
            $user->subjectLine = "Welcome to Default";
            $user->viewTemplate = "emails.user_signup";
            return $user;
        } else {
            return false;
        }
    }

    /**
     * update User Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models)
    {
        $user = User::find($models['id']);
        $user->status = $models['status'];
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date('Y-m-d H:i:s');
        $userId = $user->save();
        if ($userId)
            return true;
        else
            return false;

    }

    /**
     * Delete User
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteUser($id)
    {
        $delete = User::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * update User's Password
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateChangePassword(array $models = [])
    {
        $user = User::find(Auth::user()->id);
        $user->password = bcrypt($models['new_password']);
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date('Y-m-d H:i:s');
        $userId = $user->save();
        if ($userId)
            return true;
        else
            return false;

    }
}
