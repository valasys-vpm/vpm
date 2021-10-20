@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
    <meta name="ca-agent-id" content="{{ base64_encode($resultCAAgent->id) }}">
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
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Manage Leads</a></li>
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
                                            <h5>Lead Details</h5>
                                            <div class="float-right">
                                                <a href="{{ route('agent.lead.create', base64_encode($resultCAAgent->id)) }}">
                                                    <button type="button" class="btn btn-primary btn-square btn-sm"><i class="feather icon-plus"></i>New Lead</button>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-leads" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Company Name</th>
                                                        <th>Email Address</th>
                                                        <th>Phone Number</th>
                                                        <th>Address 1</th>
                                                        <th>Address 2</th>
                                                        <th>City</th>
                                                        <th>State</th>
                                                        <th>Zipcode</th>
                                                        <th>Country</th>
                                                        <th>Employee Size</th>
                                                        <th>Revenue</th>
                                                        <th>Company Domain</th>
                                                        <th>Website</th>
                                                        <th>Company LinkedIn URL</th>
                                                        <th>LinkedIn Profile URL</th>
                                                        <th>LinkedIn Profile SN URL</th>
                                                        <th>Created At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
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
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <!-- custom Js -->
    <script src="{{ asset('public/js/agent/lead.js?='.time()) }}"></script>
@append


