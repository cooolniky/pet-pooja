<?php namespace App\Http\Facades\Repository;

use Illuminate\Support\Facades\Facade;

/**
 * Class DepartmentFacade
 *
 * @package App\Http\Facades\Repository
 */
class DepartmentFacade extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'department';
    }
}