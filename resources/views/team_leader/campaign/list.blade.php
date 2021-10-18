@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
    <!-- toolbar css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/toolbar/css/jquery.toolbar.css')}}">

    <style>
        .table tbody tr:hover {
            -webkit-box-shadow: 0 5px 8px -6px grey;
            -moz-box-shadow: 0 5px 8px -6px grey;
            box-shadow: 0 5px 8px -6px grey;
        }
        .table td {
            padding: 5px 5px 0px 5px !important;
            vertical-align: middle !important;
            border-top: 2px solid #dad9d9 !important;
            border-bottom: 2px solid #dad9d9 !important;
        }
        .table tr td:first-child {
            border: 2px solid #dad9d9 !important;
            border-right: 0px solid transparent !important;
            border-radius: 8px 0 0 8px;
        }
        .table tr td:last-child {
            border: 2px solid #dad9d9 !important;
            border-left: 0px solid transparent !important;
            border-radius: 0px 8px 8px 0;
        }

        table.dataTable {
            border-spacing: 0px 10px !important;
        }

        /*Border Live*/
        .table tr.border-live {
            background-color: rgba(226,239,219,0.35) !important;
        }
        .table tr.border-live td {
            border-color: #92D050 !important;
        }
        .table tr.border-live td:first-child {
            border-right: 0px solid transparent !important;
        }
        .table tr.border-live td:last-child {
            border-left: 0px solid transparent !important;
        }

        /*Border Paused*/
        .table tr.border-paused {
            background-color: rgba(255,230,153,0.25) !important;
        }
        .table tr.border-paused td {
            border-color: #fbcb39 !important;
        }
        .table tr.border-paused td:first-child {
            border-right: 0px solid transparent !important;
        }
        .table tr.border-paused td:last-child {
            border-left: 0px solid transparent !important;
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
                                        <h5 class="m-b-10">My Campaigns</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">My Campaigns</a></li>
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
                                            <h5>Campaigns</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-campaigns" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Campaign ID</th>
                                                        <th>Name</th>
                                                        <th>Completion</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Deliver Count /<br> Allocation</th>
                                                        <th>Status</th>
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
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('public/js/team_leader/campaign.js?='.time()) }}"></script>
@append


