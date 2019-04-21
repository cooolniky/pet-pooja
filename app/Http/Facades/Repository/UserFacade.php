<?php namespace App\Http\Facades\Repository;

use Illuminate\Support\Facades\Facade;

/**
 * Class InoviceFacade
 *
 * @package App\Http\Facades\Repository
 */
class UserFacade extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'user';
    }
}