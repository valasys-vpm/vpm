@extends('layouts.master')

@section('stylesheet')
    @parent
    <meta name="ca-agent-id" content="{{ base64_encode($resultCAAgent->id) }}">
    <style>
        .btn:focus {
            border-color: #80bdff !important;
            outline: 0 !important;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%) !important;
        }
    </style>
@append

@section('content')
    <section class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">{{ $resultCAAgent->campaign->name }}</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.campaign.list') }}">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.campaign.show', base64_encode($resultCAAgent->campaign_id)) }}">Campaign Details</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.lead.list', base64_encode($resultCAAgent->id)) }}">Manage Leads</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Add New Lead</a></li>
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
                                <!-- [ configuration table ] start -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Add New Lead</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form id="form-lead-create" method="post" action="{{ route('agent.lead.store') }}">
                                                        @csrf
                                                        @if(isset($resultData))
                                                        <input type="hidden" id="data_id" name="data_id" value="{{ base64_encode($resultData->id) }}">
                                                        @endif
                                                        <input type="hidden" id="ca_agent_id" name="ca_agent_id" value="{{ base64_encode($resultCAAgent->id) }}">

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="first_name">First Name<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="first_name" name="first_name" placeholder="Enter first name" @if(isset($resultData)) value="{{ $resultData->first_name }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="last_name" name="last_name" placeholder="Enter last name" @if(isset($resultData)) value="{{ $resultData->last_name }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="company_name">Company Name<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="company_name" name="company_name" placeholder="Enter company name" @if(isset($resultData)) value="{{ $resultData->company_name }}" @endif>
                                                            </div>

                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="email_address">Email Address<span class="text-danger">*</span></label>
                                                                <input autofocus type="email" class="form-control btn-square" id="email_address" name="email_address" placeholder="Enter email address" @if(isset($resultData)) value="{{ $resultData->email_address }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="specific_title">Specific Title<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="specific_title" name="specific_title" placeholder="Enter title" @if(isset($resultData)) value="{{ $resultData->specific_title }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="job_level">Job Level<span class="text-info"> <small>(optional)</small></span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="job_level" name="job_level" placeholder="Enter job level" @if(isset($resultData)) value="{{ $resultData->job_level }}" @endif>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="job_role">Job Role<span class="text-info"> <small>(optional)</small></span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="job_role" name="job_role" placeholder="Enter job role" @if(isset($resultData)) value="{{ $resultData->job_role }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="phone_number">Phone Number<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="phone_number" name="phone_number" placeholder="Enter phone number" @if(isset($resultData)) value="{{ $resultData->phone_number }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="address_1">Address 1<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="address_1" name="address_1" placeholder="Enter address 1" @if(isset($resultData)) value="{{ $resultData->address_1 }}" @endif>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="address_2">Address 2<span class="text-info"> <small>(optional)</small></span></label>
                                                                <input type="text" class="form-control btn-square" id="address_2" name="address_2" placeholder="Enter address 2" @if(isset($resultData)) value="{{ $resultData->address_2 }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="city">City<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="city" name="city" placeholder="Enter city" @if(isset($resultData)) value="{{ $resultData->city }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="state">State<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="state" name="state" placeholder="Enter state" @if(isset($resultData)) value="{{ $resultData->state }}" @endif>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="zipcode">Zipcode<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="zipcode" name="zipcode" placeholder="Enter zipcode" @if(isset($resultData)) value="{{ $resultData->zipcode }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="country">Country<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="country" name="country" placeholder="Enter country" @if(isset($resultData)) value="{{ $resultData->country }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="industry">Industry<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="industry" name="industry" placeholder="Enter industry" @if(isset($resultData)) value="{{ $resultData->industry }}" @endif>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4 form-group">
                                                                <label for="employee_size">Employee/Company Size<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="employee_size" name="employee_size" placeholder="Enter size" @if(isset($resultData)) value="{{ $resultData->employee_size }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="revenue">Revenue<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="revenue" name="revenue" placeholder="Enter revenue" @if(isset($resultData)) value="{{ $resultData->revenue }}" @endif>
                                                            </div>
                                                            <div class="col-md-4 form-group">
                                                                <label for="company_domain">Company Domain<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="company_domain" name="company_domain" placeholder="Enter company domain" @if(isset($resultData)) value="{{ $resultData->company_domain }}" @endif>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label for="website">Website<span class="text-info"> <small>(optional)</small></span></label>
                                                                <input type="text" class="form-control btn-square" id="website" name="website" placeholder="Enter website" @if(isset($resultData)) value="{{ $resultData->website }}" @endif>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="company_linkedin_url">Company LinkedIn URL<span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control btn-square" id="company_linkedin_url" name="company_linkedin_url" placeholder="Enter company linkedin url" @if(isset($resultData)) value="{{ $resultData->company_linkedin_url }}" @endif>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="linkedin_profile_link">LinkedIn Profile Link<span class="text-danger">*</span></label>
                                                                <input autofocus type="text" class="form-control btn-square" id="linkedin_profile_link" name="linkedin_profile_link" placeholder="Enter linkedin profile link" @if(isset($resultData)) value="{{ $resultData->linkedin_profile_link }}" @endif>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label for="linkedin_profile_sn_link">LinkedIn Profile SN Link<span class="text-info"> <small>(optional)</small></span></label>
                                                                <input type="text" class="form-control btn-square" id="linkedin_profile_sn_link" name="linkedin_profile_sn_link" placeholder="Enter LinkedIn Profile SN Link" @if(isset($resultData)) value="{{ $resultData->linkedin_profile_sn_link }}" @endif>
                                                            </div>
                                                        </div>

                                                        <button id="btn-submit" type="submit" class="btn btn-primary btn-square float-right">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [ configuration table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    @parent
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <!-- page custom Js -->
    <script src="{{ asset('public/js/agent/lead_create.js?='.time()) }}"></script>
@append


