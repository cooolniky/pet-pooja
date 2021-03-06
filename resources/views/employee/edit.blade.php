@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_edit_title',["app_name" => __('app.app_name'),"module"=> __('app.employee')])}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css" />
@endpush
@push('internalCssLoad')
@endpush
@section('content')
    <div class="be-content">
            <div class="page-head">
            <h2>{{trans('app.employee')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('/employee/list')}}">{{trans('app.employee')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.edit')}} {{trans('app.employee')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.edit')}} {{trans('app.employee')}}</div>
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{url('/employee/update')}}" name="app_edit_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">{{trans('app.department')}}</label>
                                    <div class="col-sm-6 col-md-4">
                                        <select class="form-control input-sm" name="department_id" id="department_id">
                                            <option value="0">{{trans('app.select')}} {{trans('app.department')}}</option>
                                            @if(count($departmentData) > 0)
                                                @foreach($departmentData as $row)
                                                    <option value="{{$row->id}}" @if($details->department_id == $row->id){{"selected"}}@endif>{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Name <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="name" id="name" placeholder="Name" class="form-control input-sm required" value="{{$details->name}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Date of birth <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="dob" id="dob" placeholder="Date of birth" readonly class="form-control input-sm required" value="{{$details->dob}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Phone <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="phone" id="phone" placeholder="Phone" class="form-control input-sm required number" maxlength="10" value="{{$details->phone}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Photo <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="file" name="photo" id="photo" placeholder="Photo" class="form-control input-sm" value="" />

                                        <input type="hidden" name="old_photo" value="{{$details->photo}}" id="old_photo" />
                                        <img src="{{url('img/photos',[$details->photo])}}" class="bm_image" width="100px" height="100px" />

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Email <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="email" id="email" placeholder="Email Address" class="form-control input-sm required email" value="{{$details->email}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Salary <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="salary" id="salary" placeholder="Salary" class="form-control input-sm required digit" value="{{$details->salary}}" />
                                    </div>
                                </div>

                                <?php
                                    $status_check = "";
                                    if ($details->status == '1') {
                                        $status_check = "checked";
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Status<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="switch-button switch-button-lg">
                                            <input name="status" id="swt1" {{$status_check}} type="checkbox" value="1" />
                                            <span>
                                                 <label for="swt1"></label>
                                             </span>
                                        </div>
                                    </div>
                                </div>

                                {{ csrf_field() }}
                                <input type="hidden" name="id" id="id" value="{{$details->id}}" />
                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">Update {{trans('app.employee')}}</button>
                                        <a href="{{url('/employee/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
@endpush
@push('internalJsLoad')
<script type="text/javascript">
        app.validate.init();
        app.datepicker("dob");
</script>
@endpush