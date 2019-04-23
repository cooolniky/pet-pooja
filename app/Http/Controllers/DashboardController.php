<?php

namespace App\Http\Controllers;

use App\Repositories\Contract\DepartmentInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $department;
    public function __construct(DepartmentInterface $departmentInterface)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->department = $departmentInterface;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data['dashboardTab'] = 'active';
        $data['departmentData'] = $this->department->getCollection();
        return view('dashboard',$data);
    }
}
