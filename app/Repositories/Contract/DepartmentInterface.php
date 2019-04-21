<?php namespace App\Repositories\Contract;

/**
 * Interface DepartmentInterface
 * @package App\Repositories\Contract
 */
interface DepartmentInterface
{
    /**
     *  Get the fields for department list
     *
     * @return mixed
     */
    public function getCollection();

    /**
     *  Get the fields for department list
     *
     * @return mixed
     */
    public function getDatatableCollection();

    /**
     * get Department By fieldname getDepartmentByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getDepartmentByField($id, $field_name);

    /**
     * Add & update Department addDepartment
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addDepartment($models);

    /**
     * update Department Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models);

    /**
     * Delete Department
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteDepartment($id);
}
