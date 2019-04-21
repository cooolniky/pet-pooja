<?php

namespace App\Http\Facades\Repository;
use App\Repositories\Contract\UserInterface;
use App\Models\Role;

/**
 * Class UserFacadeClass
 *
 */
class UserFacadeClass
{

    protected $user;
    protected $role;
    /**
     * User constructor.
     *
     * @param UserInterface $blockedAdjRepo
     */
    public function __construct(UserInterface $repo,Role $role)
    {
        $this->user = $repo;
        $this->role = $role;
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function view() {
        $data['userData'] = $this->user->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['userTab'] = "active";
        return $data;
    }

    /**
     * @param $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     * @author Nikhil.Jain
     */
    public function getDataTable($request) {

        // get the fields for user list
        $userData = $this->user->getDatatableCollection();

        // get the filtered data of user list
        $userFilteredData = $this->user->getFilteredData($userData,$request);

        //  Sorting user data base on requested sort order
        $userCount = $this->user->getCount($userFilteredData);

        // Sorting user data base on requested sort order
        if (isset(config('constant.userDataTableFieldArray')[$request->order['0']['column']])) {
            $userSortData = $this->user->getSortData($userFilteredData,$request);
        } else {
            $userSortData = $this->user->getSortDefaultDataByRaw($userFilteredData,'users.id', 'desc');
        }

        // get collection of user
        $userData = $this->user->getData($userSortData,$request);

        $appData = array();
        foreach ($userData as $userData) {
            $row = array();
            $row[] = $userData->first_name;
            $row[] = $userData->last_name;
            $row[] = ($userData->Role) ? $userData->Role->code : "---";
            $row[] = $userData->email;
            $row[] = view('datatable.switch', ['module' => "user",'status' => $userData->status, 'id' => $userData->id])->render();
            $row[] = view('datatable.action', ['module' => "user",'type' => $userData->role_id, 'id' => $userData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $userCount,
            'recordsFiltered' => $userCount,
            'data' => $appData,
        ];
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function create() {
        $data['masterManagementTab'] = "active open";
        $data['userTab'] = "active";
        $data['userData'] = $this->user->getCollection();
        $data['roleData'] = $this->role->getCollection();
        return $data;
    }

    /**
     * Display the specified user.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function edit($id)
    {
        $data['details'] = $this->user->getUserByField($id, 'id');
        $data['userData'] = $this->user->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['userTab'] = "active";
        return $data;
    }

    /**
     * Display the specified user profile.
     *
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function profile($id,$field)
    {
        $data['profileTab'] = "active";
        $data['details'] = $this->user->getUserByField($id,$field);
        return $data;
    }

    /**
     * Store and Update user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function insertAndUpdateUser($requestData) {
        return $this->user->addUser($requestData);
    }

    /**
     * @param $requestData
     * @return bool
     * @author Nikhil.Jain
     */
    public function updateStatus($requestData) {
        return $this->user->updateStatus($requestData);
    }

    /**
     * @param $id
     * @return bool
     * @author Nikhil.Jain
     */
    public function deleteUser($id) {
        return $this->user->deleteUser($id);
    }

    /**
     * @param $requestData
     * @return bool
     * @author Nikhil.Jain
     */
    public function updateChangePassword($requestData) {
        return $this->user->updateChangePassword($requestData);
    }
}