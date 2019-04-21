<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\SendMail;
use Validator;
use Event;
use Hash;
use User;
use DB;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'checkRole']);
    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = User::view();
        return view('user.userlist', $viewData);
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Nikhil.Jain
     */
    public function datatable(Request $request)
    {
        return User::getDataTable($request);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formData = User::create();
        return view('user.add', $formData);
    }

    /**
     * Display the specified user.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editFormData = User::edit($id);
        return view('user.edit', $editFormData);
    }

    /**
     * Display the specified user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $profileData = User::profile(Auth::user()->id, 'id');
        return view('user.profile', $profileData);
    }

    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */
    public function customeValidate($data, $mode)
    {
        $rules = array(
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'role_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:15|Same:confirm_password'
        );

        if ($mode == "edit") {
            $rules['password'] = '';
            $rules['email'] = '';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "user/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "user/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $adduser = User::insertAndUpdateUser($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('user/add')->withInput();

        }
        if ($adduser) {
            Event::fire(new SendMail($adduser));
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.user')]));
            return redirect('user/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.user'),"action"=>__('app.add')]));
            return redirect('user/add')->withInput();
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $updateuser = User::insertAndUpdateUser($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('user/edit/' . $request->get('id'))->withInput();

        }

        if ($updateuser) {

            //  if change_redirect_state  exists then user redirect to user profile
            if(!empty($request->change_redirect_state) && $request->change_redirect_state == 1){
                $request->session()->flash('alert-success', trans('app.user_profile_update_success'));
                return redirect('user/profile');
            }
            $request->session()->flash('alert-success', __('app.default_edit_success',["module" => __('app.user')]));
            return redirect('user/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.user'),"action"=>__('app.update')]));
            return redirect('user/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        // Start Communicate with database
        DB::beginTransaction();
        try{
            $updateUser = User::updateStatus($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
        }

        if ($updateUser) {
            $request->session()->flash('alert-success', __('app.default_status_success',["module" => __('app.user')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.user'),"action"=>__('app.change_status')]));
        }
        echo 1;
    }

    /**
     * Delete the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deleteUser = User::deleteUser($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', __('app.default_delete_success',["module" => __('app.user')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.user'),"action"=>__('app.delete')]));
        }
        echo 1;
    }

    /**
     * Display change password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        $data['changePasswordTab'] = "active";
        return view('user.change_password', $data);
    }


    /**
     * Update Password of logged in user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(request $request)
    {
        $rules = array(
            'old_password' => 'required|min:6|Different:new_password|max:15',
            'new_password' => 'required|min:6|Same:confirm_password|max:15',
            'confirm_password' => 'required|min:6|max:15'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('change-password')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        if (Hash::check($request->old_password, Auth::user()->password)) {

            // Start Communicate with database
            DB::beginTransaction();
            try{
                $updateUser = User::updateChangePassword($request->all());
                DB::commit();
            } catch (\Exception $e) {
                //exception handling
                DB::rollback();
                $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
                $request->session()->flash('alert-danger', $errorMessage);
                return redirect('change-password');

            }

        } else {
            $request->session()->flash('alert-danger', trans('app.old_password_error'));
            return redirect('change-password');
        }


        if ($updateUser) {
            $request->session()->flash('alert-success', trans('app.user_password_success'));
            return redirect('change-password');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.user'),"action"=>__('app.delete')]));
            return redirect('change-password');
        }
    }

}
