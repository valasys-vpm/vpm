@extends('layouts.master')

@section('title', '| My Profile')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">User Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('quality_analyst.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">My Profile</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <!-- [ task-detail ] start -->
                                <div class="col-xl-4 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col-auto">
                                                    <img class="img-fluid rounded-circle" style="width:80px;" src="{{asset('public/template/assets/images/user/avatar-2.jpg')}}" alt="dashboard-user">
                                                </div>
                                                <div class="col">
                                                    <h5>{{ $resultUser->full_name }}</h5>
                                                    <span>{{ $resultUser->designation->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>User Details</h5>
                                        </div>
                                        <div class="card-block task-details">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <td>Email:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->email }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Employee Code:</td>
                                                    <td class="text-right"><span class="float-right">{{ $resultUser->employee_code }}</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Department:</td>
                                                    <td class="text-right">{{ $resultUser->department->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Designation:</td>
                                                    <td class="text-right">{{ $resultUser->designation->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Status:</td>
                                                    <td class="text-right">
                                                        @switch($resultUser->status)
                                                            @case(1) <span @class('text-success')>Active</span> @break
                                                            @case(0) <span @class('text-danger')>Inactive</span> @break
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-3"><i class="fas fa-edit m-r-5"></i> Update your profile</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-md-8 offset-2">
                                                    <form id="form-profile-edit">
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="first_name" name="first_name" value="{{ $resultUser->first_name }}" placeholder="Enter first name">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="last_name" name="last_name" value="{{ $resultUser->last_name }}" placeholder="Enter last name">
                                                            </div>
                                                        </div>
                                                        <button id="form-profile-edit-submit" type="button" class="btn btn-primary btn-square float-right">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-3"><i class="fas fa-edit m-r-5"></i> Change Password</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-md-8 offset-2">
                                                    <form id="form-change-password" method="post" action="{{ route('quality_analyst.user.change_password') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="password">New Password<span class="text-danger">*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control btn-square" id="password" name="password" placeholder="Enter new password">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="feather icon-eye-off" style="cursor: pointer;" onclick="showPassword('password', this);"></i></span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <label for="confirm_password">Confirm Password<span class="text-danger">*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <input type="password" class="form-control btn-square" id="confirm_password" name="confirm_password" placeholder="Confirm password">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="feather icon-eye-off" style="cursor: pointer;" onclick="showPassword('confirm_password', this);"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-dark btn-square float-right">Change Password</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ task-detail ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('public/js/quality_analyst/my_profile.js?='.time()) }}"></script>
@append
