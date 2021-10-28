@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
    <meta name="ca-agent-id" content="{{ base64_encode($resultCAAgent->id) }}">

    <style>
        .table td{
            padding: 5px 10px !important;
        }

        table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before {
            position: absolute;
            top: 7px;
        }

        table td:first-child {
            padding-left: 30px !important;
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
                                        <h5 class="m-b-10">Manage Data</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.campaign.list') }}">My Campaigns</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.campaign.show', base64_encode($resultCAAgent->id)) }}">Campaign Details</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">View Data</a></li>
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
                                            <h5>Data List</h5>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-square btn-sm" data-toggle="modal" data-target="#modal-import-data"><i class="feather icon-upload"></i>Import</button>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-data" class="display table dt-responsive nowrap table-hover" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Company Name</th>
                                                        <th>Email Address</th>
                                                        <th>Specific Title</th>
                                                        <th>Job Level</th>
                                                        <th>Job Role</th>
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
    <script src="{{ asset('public/js/agent/data.js?='.time()) }}"></script>
@append


