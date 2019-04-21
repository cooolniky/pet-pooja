<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Employee;
use DB;

class EmployeeController extends Controller
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
     * Display a listing of the employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!empty($request->noDepartment) && $request->noDepartment != 'no-department') {
            return abort(404);
        }
        $viewData = Employee::view();
        if(!empty($request->noDepartment) && $request->noDepartment == 'no-department') {
            $viewData['noDepartment'] = true;
            $viewData['noDepartmentTab'] = "active";
            unset($viewData['employeeTab']);
        }
        return view('employee.list', $viewData);
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Nikhil.Jain
     */
    public function datatable(Request $request)
    {
        return Employee::getDataTable($request);
    }

    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formData = Employee::create();
        return view('employee.add', $formData);
    }

    /**
     * Display the specified employee.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editFormData = Employee::edit($id);
        return view('employee.edit', $editFormData);
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
        $rules = ['name' => 'required|max:100',
                  'dob' => 'required',
                  'salary' => 'required',
                  'photo' => 'required|mimes:jpeg,jpg,png,svg',
                  'email' => 'required|unique:employee,email',
                  'phone' => 'required|unique:employee,phone'];
        if ($mode == "edit") {
            $rules['email'] = 'required|unique:employee,email,'.$data['id'].',id';
            $rules['phone'] = 'required|unique:employee,phone,'.$data['id'].',id';
            $rules['photo'] = '';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "employee/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "employee/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created employee in storage.
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
            $addemployee = Employee::insertAndUpdateEmployee($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = $e->getMessage();
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('employee/add')->withInput();

        }
        if ($addemployee) {
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.employee')]));
            return redirect('employee/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.employee'),"action"=>__('app.add')]));
            return redirect('employee/add')->withInput();
        }
    }

    /**
     * Update the specified employee in storage.
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
            $updateemployee = Employee::insertAndUpdateEmployee($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = $e->getMessage();
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('employee/edit/' . $request->get('id'))->withInput();

        }

        if ($updateemployee) {

            //  if change_redirect_state  exists then employee redirect to employee profile
            if(!empty($request->change_redirect_state) && $request->change_redirect_state == 1){
                $request->session()->flash('alert-success', trans('app.employee_profile_update_success'));
                return redirect('employee/profile');
            }
            $request->session()->flash('alert-success', __('app.default_edit_success',["module" => __('app.employee')]));
            return redirect('employee/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.employee'),"action"=>__('app.update')]));
            return redirect('employee/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        // Start Communicate with database
        DB::beginTransaction();
        try{
            $updateEmployee = Employee::updateStatus($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
        }

        if ($updateEmployee) {
            $request->session()->flash('alert-success', __('app.default_status_success',["module" => __('app.employee')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.employee'),"action"=>__('app.change_status')]));
        }
        echo 1;
    }

    /**
     * Delete the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deleteEmployee = Employee::deleteEmployee($request->id);
        if ($deleteEmployee) {
            $request->session()->flash('alert-success', __('app.default_delete_success',["module" => __('app.employee')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.employee'),"action"=>__('app.delete')]));
        }
        echo 1;
    }
}
