@if($mode == 'highest_salary')

        <table class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget">
            <thead>
            <tr>
                <td>Employee Name</td>
                <td>Salary</td>
            </tr>
            </thead>
            @if(!empty($employeeDetailWithHighestSalary))
            <tbody>
            @foreach($employeeDetailWithHighestSalary as $employee)
                <tr>
                    <td>{{$employee->name}}</td>
                    <td>{{$employee->salary}}</td>
                </tr>
            @endforeach
            </tbody>
            @else
                <tbody>
                    <tr>
                        <td colspan="2">No record found</td>
                    </tr>
                </tbody>
            @endif
        </table>
@endif

@if($mode == 'youngest_employee')
        <table class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget">
            <thead>
            <tr>
                <td>Employee Name</td>
                <td>Age</td>
            </tr>
            </thead>
            @if(!empty($youngestEmployees))
            <tbody>
                @foreach($youngestEmployees as $employee)
                    <tr>
                        <td>{{$employee->name}}</td>
                        <td>{{$employee->age}}</td>
                    </tr>
                @endforeach
            </tbody>
            @else
            <tbody>
                <tr>
                    <td colspan="2">No record found</td>
                </tr>
            </tbody>
            @endif
        </table>
@endif
