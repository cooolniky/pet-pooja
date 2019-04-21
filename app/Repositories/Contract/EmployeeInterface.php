<?php namespace App\Repositories\Contract;

/**
 * Interface EmployeeInterface
 * @package App\Repositories\Contract
 */
interface EmployeeInterface
{
    /**
     *  Get the fields for employee list
     *
     * @return mixed
     */
    public function getCollection();

    /**
     *  Get the fields for employee list
     *
     * @return mixed
     */
    public function getDatatableCollection();

    /**
     * get Employee By fieldname getEmployeeByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getEmployeeByField($id, $field_name);

    /**
     * Add & update Employee addEmployee
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addEmployee($models);

    /**
     * update Employee Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models);

    /**
     * Delete Employee
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteEmployee($id);
}
