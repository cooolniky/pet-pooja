<?php namespace App\Http\Facades\Repository;

use Illuminate\Support\Facades\Facade;

/**
 * Class EmployeeFacade
 *
 * @package App\Http\Facades\Repository
 */
class EmployeeFacade extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'employee';
    }
}