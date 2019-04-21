<?php

namespace App\Http\Facades\Repository;
use App\Repositories\Contract\EmployeeInterface;
use App\Repositories\Contract\DepartmentInterface;

/**
 * Class EmployeeFacadeClass
 *
 */
class EmployeeFacadeClass
{

    protected $employee;
    protected $departmentRepo;
    /**
     * Employee constructor.
     *
     * @param EmployeeInterface $blockedAdjRepo
     */
    public function __construct(EmployeeInterface $repo, DepartmentInterface $departmentInterface)
    {
        $this->employee = $repo;
        $this->departmentRepo = $departmentInterface;
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function view() {
        $data['employeeData'] = $this->employee->getCollection();
        $data['departmentData'] = $this->departmentRepo->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['employeeTab'] = "active";
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

        // get the fields for employee list
        $employeeData = $this->employee->getDatatableCollection();

        // get the filtered data of employee list
        $employeeFilteredData = $this->employee->getFilteredData($employeeData,$request);

        //  Sorting employee data base on requested sort order
        $employeeCount = $this->employee->getCount($employeeFilteredData);

        // Sorting employee data base on requested sort order
        if (isset(config('constant.employeeDataTableFieldArray')[$request->order['0']['column']])) {
            $employeeSortData = $this->employee->getSortData($employeeFilteredData,$request);
        } else {
            $employeeSortData = $this->employee->getSortDefaultDataByRaw($employeeFilteredData,'employee.id', 'desc');
        }

        // get collection of employee
        $employeeData = $this->employee->getData($employeeSortData,$request);

        $appData = array();
        foreach ($employeeData as $employeeData) {
            $row = array();
            $row[] = $employeeData->name;
            $row[] = $employeeData->email;
            $row[] = (!empty($employeeData->Department->name)) ? $employeeData->Department->name : '---';
            $row[] = view('datatable.switch', ['module' => "employee",'status' => $employeeData->status, 'id' => $employeeData->id])->render();
            $row[] = view('datatable.action', ['module' => "employee",'type' => '2', 'id' => $employeeData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $employeeCount,
            'recordsFiltered' => $employeeCount,
            'data' => $appData,
        ];
    }

    /**
     * @return mixed
     * @author Nikhil.Jain
     */
    public function create() {
        $data['masterManagementTab'] = "active open";
        $data['employeeTab'] = "active";
        $data['departmentData'] = $this->departmentRepo->getCollection();
        return $data;
    }

    /**
     * Display the specified employee.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function edit($id)
    {
        $data['details'] = $this->employee->getEmployeeByField($id, 'id');
        $data['departmentData'] = $this->departmentRepo->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['employeeTab'] = "active";
        return $data;
    }

    /**
     * Store and Update employee in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Nikhil.Jain
     */
    public function insertAndUpdateEmployee($requestData) {
        return $this->employee->addEmployee($requestData);
    }

    /**
     * @param $requestData
     * @return bool
     * @author Nikhil.Jain
     */
    public function updateStatus($requestData) {
        return $this->employee->updateStatus($requestData);
    }

    /**
     * @param $id
     * @return bool
     * @author Nikhil.Jain
     */
    public function deleteEmployee($id) {
        return $this->employee->deleteEmployee($id);
    }
}