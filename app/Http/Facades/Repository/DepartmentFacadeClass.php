<?php

namespace App\Http\Facades\Repository;
use App\Repositories\Contract\DepartmentInterface;
use Carbon\Carbon;
/**
 * Class DepartmentFacadeClass
 *
 */
class DepartmentFacadeClass
{

    protected $department;
    /**
     * Department constructor.
     *
     * @param DepartmentInterface $blockedAdjRepo
     */
    public function __construct(DepartmentInterface $repo)
    {
        $this->department = $repo;
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function view() {
        $data['departmentData'] = $this->department->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['departmentTab'] = "active";
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

        // get the fields for department list
        $departmentData = $this->department->getDatatableCollection();

        // get the filtered data of department list
        $departmentFilteredData = $this->department->getFilteredData($departmentData,$request);

        //  Sorting department data base on requested sort order
        $departmentCount = $this->department->getCount($departmentFilteredData);

        // Sorting department data base on requested sort order
        if (isset(config('constant.departmentDataTableFieldArray')[$request->order['0']['column']])) {
            $departmentSortData = $this->department->getSortData($departmentFilteredData,$request);
        } else {
            $departmentSortData = $this->department->getSortDefaultDataByRaw($departmentFilteredData,'department.id', 'desc');
        }

        // get collection of department
        $departmentData = $this->department->getData($departmentSortData,$request);

        $appData = array();
        foreach ($departmentData as $departmentData) {
            $row = array();
            $row[] = $departmentData->name;
            $row[] = view('datatable.switch', ['module' => "department",'status' => $departmentData->status, 'id' => $departmentData->id])->render();
            $row[] = (!empty($departmentData->EmployeeWithHighestSalary[0]->name)) ? $departmentData->EmployeeWithHighestSalary[0]->name : '---';
            $row[] = (!empty($departmentData->YoungestEmployee[0]->name)) ? $departmentData->YoungestEmployee[0]->name . '('.$this->getAge($departmentData->YoungestEmployee[0]->dob).')' : '---';
            $row[] = view('datatable.action', ['module' => "department",'type' => '2', 'id' => $departmentData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $departmentCount,
            'recordsFiltered' => $departmentCount,
            'data' => $appData,
        ];
    }

    /**
     * @param $dob
     * @return string
     * @author Nikhil.Jain
     */
    public function getAge($dob) {
        $years = Carbon::parse($dob)->age. " year old";
        return $years;
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function create() {
        $data['masterManagementTab'] = "active open";
        $data['departmentTab'] = "active";
        $data['departmentData'] = $this->department->getCollection();
        return $data;
    }

    /**
     * Display the specified department.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function edit($id)
    {
        $data['details'] = $this->department->getDepartmentByField($id, 'id');
        $data['departmentData'] = $this->department->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['departmentTab'] = "active";
        return $data;
    }

    /**
     * Store and Update department in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function insertAndUpdateDepartment($requestData) {
        return $this->department->addDepartment($requestData);
    }

    /**
     * @param $requestData
     * @return bool
     * @author Nikhil.Jain
     */
    public function updateStatus($requestData) {
        return $this->department->updateStatus($requestData);
    }

    /**
     * @param $id
     * @return bool
     * @author Nikhil.Jain
     */
    public function deleteDepartment($id) {
        return $this->department->deleteDepartment($id);
    }
}