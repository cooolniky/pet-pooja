@extends('layouts.common')
@section('pageTitle')
    {{__('app.dashboard_title',["app_name"=> __('app.app_name')])}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery-jvectormap-1.2.2.css')}}" type="text/css" />
<link rel="stylesheet" href="{{url('css/plugins/jqvmap.min.css')}}" type="text/css" />
@endpush
@push('internalCssLoad')
<style>
    .highest_salary,.youngest_employee{
        max-height: 350px;
        overflow-y: scroll;
    }
</style>
@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <div class="form-group">
                <label class="col-md-2 control-label" style="font-weight: bold; font-size: 14px;">Select {{trans('app.department')}}</label>
                <div class="col-sm-6 col-md-3">
                    <select class="form-control input-sm" name="department_id" id="department_id">
                        <option value="0">{{trans('app.select')}} {{trans('app.department')}}</option>
                        @if(!empty($departmentData))
                            @foreach($departmentData as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="main-content container-fluid">


            <div class="row">
                <div class="col-md-6">
                    <div class="widget widget-fullwidth">
                        <div class="widget-head">
                            <!--                            <div class="tools"><span class="icon mdi mdi-chevron-down"></span><span class="icon mdi mdi-refresh-sync"></span><span class="icon mdi mdi-close"></span></div>-->
                            <span class="title">Employee (Highest salary)</span><span class="description">&nbsp;</span>
                        </div>
                        <div class="widget-chart-container highest_salary">
                            {{--<div id="line-chart3" style="height: 220px;"></div>
                            <div class="chart-table xs-pt-15">

                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="widget widget-fullwidth">
                        <div class="widget-head">
                            <span class="title">Employee (Youngest)</span><span class="description">&nbsp;</span>
                        </div>
                        <div class="widget-chart-container youngest_employee">

                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/plugins/jquery-jvectormap-1.2.2.min.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.vmap.min.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.vmap.world.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/countUp.min.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.flot.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.flot.pie.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.flot.resize.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.flot.orderBars.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/curvedLines.js')}}" type="text/javascript"></script>

@endpush
@push('internalJsLoad')
<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('change',"#department_id", function () {
            var url = app.config.SITE_PATH + 'department/employee_list';
            $(".highest_salary").html('');
            $(".youngest_employee").html('');
            var id = $(this).val();
            if(id == 0) {
                return false;
            }

            app.showLoader(".main-content");

            $.ajax({
                type: "POST",
                url: url,
                data: { id : id,_token: csrf_token}
            }).done(function(data){
                if(data.employeeCountWithHighestSalary > 0) {
                    $(".highest_salary").html(data.employeeDetailWithHighestSalary);
                }
                if(data.countYoungestEmployees > 0) {
                    $(".youngest_employee").html(data.youngestEmployees);
                }
                app.hideLoader(".main-content");
            });

        });
    });
</script>
@endpush