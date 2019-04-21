<?php namespace App\Http\Facades\Repository;

/**
 * Created by PhpStorm.
 * User: abhiteshd
 * Date: 4/11/16
 * Time: 7:00 PM
 */

class BaseRepositoryFacade
{

    /**
     * @param       $matchThese
     * @param array $selectColumns
     * @param bool  $count
     * @return mixed
     */
    public function getDetailsByColumn($matchThese, $selectColumns = array('id'), $count = false)
    {
        return $this->getRepo()->getDetailsByColumn($matchThese, $selectColumns, $count);
    }

    /**
     * Wrapper function for getDetailsByColumn
     *
     * @author sahil.purav
     * @param       $matchThese
     * @param array $selectColumns
     * @return array
     */
    public function getDetailsArrayByColumn($matchThese, $selectColumns = array('id'), $count = false, $orderByField = 'id', $orderByAscDesc = 'asc')
    {
        return $this->getRepo()->getDetailsArrayByColumn($matchThese, $selectColumns, $count, $orderByField, $orderByAscDesc);
    }

    /**
     * @param array  $data
     * @param        $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->getRepo()->update($data, $id, $attribute);
    }

    /**
     * @param array $data
     * @author pratik.joshi
     * @desc we have to call repo's method via facade. So building method.
     */
    public function save($data){
        return $this->getRepo()->save($data);
    }


}