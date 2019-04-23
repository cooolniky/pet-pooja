<?php namespace App\Repositories\Eloquent;

use App\Repositories\Contract\EmployeeInterface;
use App\Models\Employee;
use Auth;
use App\Traits\CommonModelTrait;
use App\Helpers\LaraHelpers;

/**
 * Class EmployeeRepository
 *
 * @package App\Repositories\Eloquent
 */
class EmployeeRepository implements EmployeeInterface
{

    use CommonModelTrait;
    /**
     * Get all Employee getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {
        return Employee::get();
    }

    /**
     * Get all Employee with role and ParentEmployee relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Employee::with('Department');
    }

    /**
     * use for sorting
     *
     * @return array
     */
    public function getSortFields($index)
    {
        $sortableFields = [
            "name",
            "email",
            "status",
            ""
        ];

        return $sortableFields[ $index ];
    }

    /**
     * get Employee By fieldname getEmployeeByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getEmployeeByField($id, $field_name)
    {
        return Employee::where($field_name, $id)->first();
    }

    /**
     * Add & update Employee addEmployee
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addEmployee($models)
    {
        $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'photos' . DIRECTORY_SEPARATOR;

        if (isset($models['id'])) {
            $employee = Employee::find($models['id']);
            if(!empty($models['old_photo'])) {
                $employee->photo = LaraHelpers::upload_image($filepath, $models['photo'], $models['old_photo']);
            }
        } else {
            $employee = new Employee;
            $employee->photo = LaraHelpers::upload_image($filepath, $models['photo'], '');
            $employee->created_date = date('Y-m-d H:i:s');
            $employee->created_by = Auth::user()->id;
        }

        $employee->name = $models['name'];
        $employee->department_id = $models['department_id'];
        $employee->dob = date('Y-m-d', strtotime($models['dob']));
        $employee->phone = $models['phone'];
        $employee->email = $models['email'];
        $employee->salary = $models['salary'];
        if (isset($models['status'])) {
            $employee->status = $models['status'];
        } else {
            $employee->status = 0;
        }

        $employee->last_modified_by = Auth::user()->id;
        $employee->last_modified_date = date('Y-m-d H:i:s');
        $employeeId = $employee->save();

        if ($employeeId) {
            return $employee;
        } else {
            return false;
        }
    }

    /**
     * update Employee Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models)
    {
        $employee = Employee::find($models['id']);
        $employee->status = $models['status'];
        $employee->last_modified_by = Auth::user()->id;
        $employee->last_modified_date = date('Y-m-d H:i:s');
        $employeeId = $employee->save();
        if ($employeeId)
            return true;
        else
            return false;

    }

    /**
     * Delete Employee
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteEmployee($id)
    {
        $delete = Employee::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getEmployeeDetailWithHighestSalary($id) {
        $maxSalary = Employee::where('department_id',$id)->where('status',1)->max('salary');
        return Employee::where('department_id',$id)->where('salary',$maxSalary)->where('status',1)->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public function getYoungestEmployees($id) {
        $minDob = Employee::where('department_id',$id)->where('status',1)->min('dob');
        return Employee::where('department_id',$id)->where('status',1)->where('dob',$minDob)->get();
    }
}
