<?php namespace App\Repositories\Eloquent;

use App\Repositories\Contract\DepartmentInterface;
use App\Models\Department;
use Auth;
use App\Traits\CommonModelTrait;


/**
 * Class DepartmentRepository
 *
 * @package App\Repositories\Eloquent
 */
class DepartmentRepository implements DepartmentInterface
{

    use CommonModelTrait;
    /**
     * Get all Department getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {
        return Department::where('status',1)->orderBy('name','asc')->get();
    }

    /**
     * Get all Department with role and ParentDepartment relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Department::with('EmployeeWithHighestSalary','YoungestEmployee');
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
            "status",
            ""
        ];

        return $sortableFields[ $index ];
    }

    /**
     * get Department By fieldname getDepartmentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getDepartmentByField($id, $field_name)
    {
        return Department::where($field_name, $id)->first();
    }

    /**
     * Add & update Department addDepartment
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addDepartment($models)
    {
        if (isset($models['id'])) {
            $department = Department::find($models['id']);
        } else {
            $department = new Department;
            $department->created_date = date('Y-m-d H:i:s');
            $department->created_by = Auth::user()->id;
        }

        $department->name = $models['name'];
        if (isset($models['status'])) {
            $department->status = $models['status'];
        } else {
            $department->status = 0;
        }

        $department->last_modified_by = Auth::user()->id;
        $department->last_modified_date = date('Y-m-d H:i:s');
        $departmentId = $department->save();

        if ($departmentId) {
            return $department;
        } else {
            return false;
        }
    }

    /**
     * update Department Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models)
    {
        $department = Department::find($models['id']);
        $department->status = $models['status'];
        $department->last_modified_by = Auth::user()->id;
        $department->last_modified_date = date('Y-m-d H:i:s');
        $departmentId = $department->save();
        if ($departmentId)
            return true;
        else
            return false;

    }

    /**
     * Delete Department
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteDepartment($id)
    {
        $delete = Department::where('id', $id)->delete();
        if ($delete)
            return true;
        else
            return false;

    }
}
