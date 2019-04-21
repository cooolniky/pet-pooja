<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Department;
use DB;

class DepartmentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the department.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = Department::view();
        return view('department.list', $viewData);
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Nikhil.Jain
     */
    public function datatable(Request $request)
    {
        return Department::getDataTable($request);
    }

    /**
     * Show the form for creating a new department.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formData = Department::create();
        return view('department.add', $formData);
    }

    /**
     * Display the specified department.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editFormData = Department::edit($id);
        return view('department.edit', $editFormData);
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
        $rules = ['name' => 'required|unique:department,name|max:100'];
        if ($mode == "edit") {
            $rules = ['name' => 'required|unique:department,name,'.$data['id'].',id|max:100'];
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "department/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "department/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created department in storage.
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
            $adddepartment = Department::insertAndUpdateDepartment($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = $e->getMessage();
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('department/add')->withInput();

        }
        if ($adddepartment) {
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.department')]));
            return redirect('department/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.department'),"action"=>__('app.add')]));
            return redirect('department/add')->withInput();
        }
    }

    /**
     * Update the specified department in storage.
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
            $updatedepartment = Department::insertAndUpdateDepartment($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = $e->getMessage();
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('department/edit/' . $request->get('id'))->withInput();

        }

        if ($updatedepartment) {

            //  if change_redirect_state  exists then department redirect to department profile
            if(!empty($request->change_redirect_state) && $request->change_redirect_state == 1){
                $request->session()->flash('alert-success', trans('app.department_profile_update_success'));
                return redirect('department/profile');
            }
            $request->session()->flash('alert-success', __('app.default_edit_success',["module" => __('app.department')]));
            return redirect('department/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.department'),"action"=>__('app.update')]));
            return redirect('department/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified department in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        // Start Communicate with database
        DB::beginTransaction();
        try{
            $updateDepartment = Department::updateStatus($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
        }

        if ($updateDepartment) {
            $request->session()->flash('alert-success', __('app.default_status_success',["module" => __('app.department')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.department'),"action"=>__('app.change_status')]));
        }
        echo 1;
    }

    /**
     * Delete the specified department in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deleteDepartment = Department::deleteDepartment($request->id);
        if ($deleteDepartment) {
            $request->session()->flash('alert-success', __('app.default_delete_success',["module" => __('app.department')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.department'),"action"=>__('app.delete')]));
        }
        echo 1;
    }
}
