<?php namespace App\Repositories\Contract;

/**
 * Interface BankTransferIntimationInterface
 * @package App\Repositories\Contract
 */
interface UserInterface
{
    /**
     *  Get the fields for user list
     *
     * @return mixed
     */
    public function getCollection();

    /**
     *  Get the fields for user list
     *
     * @return mixed
     */
    public function getDatatableCollection();

    /**
     * get User By fieldname getUserByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getUserByField($id, $field_name);

    /**
     * Add & update User addUser
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addUser($models);

    /**
     * update User Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus($models);

    /**
     * Delete User
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteUser($id);

    /**
     * update User's Password
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateChangePassword(array $models = []);
}
