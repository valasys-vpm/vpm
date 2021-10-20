@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">
    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
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
                                        <h5 class="m-b-10">Campaign Assign</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Assign</a></li>
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
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5><i class="feather icon-shuffle m-r-5"></i> Campaign Assign</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button style="display: none;" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <button type="button" class="btn minimize-card" id="filter-card-toggle"><i class="feather icon-plus"></i></button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right" style="display: none;">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block" style="display: none;">
                                            <form id="form-campaign-assign">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label for="campaign_status">Select Campaign(s)</label>
                                                        <select class="form-control btn-square p-1 pl-2" id="campaign_list" name="campaign_list" style="height: unset;" required>
                                                            <option value="">--- Select Campaign ---</option>
                                                            @foreach($resultCampaigns as $campaign)
                                                                <option id="campaign_list_{{ $campaign->campaign_id }}" value="{{ $campaign->campaign_id }}" data-caratl-id="{{ $campaign->id }}" data-name="{{ $campaign->campaign->name }}" data-end-date="{{ $campaign->display_date }}" data-allocation="{{ $campaign->allocation }}">{{ $campaign->campaign->campaign_id.' - '.$campaign->campaign->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label for="user_list">Select User(s)</label>
                                                        <select class="form-control btn-square p-1 pl-2 select2-multiple" id="user_list" name="user_list[]" style="height: unset;" multiple>
                                                            @foreach($resultUsers as $user)
                                                                <option id="user_list_{{ $user->id }}" value="{{ $user->id }}" data-name="{{ $user->first_name.' '.$user->last_name }}">{{ $user->first_name.' '.$user->last_name.' - [ '.$user->role->name.' ]' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button id="button-reset-form-campaign-assign" type="reset" class="btn btn-outline-dark btn-square btn-sm"><i class="fas fa-undo m-r-5"></i>Reset</button>
                                                        <button id="button-campaign-assign" type="button" class="btn btn-outline-primary btn-square btn-sm"><i class="fas fa-filter m-r-5"></i>Apply</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- [ configuration table ] start -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign List</h5>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-campaigns" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Campaign ID</th>
                                                        <th>Name</th>
                                                        <th>Users</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Deliver Count <br>/ Allocation</th>
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

    <div id="modal-campaign-assign" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form id="form-campaign-user-assignment" method="post" action="{{ route('team_leader.campaign_assign.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="campaign_assign_ratl_id" name="data[0][campaign_assign_ratl_id]" value="">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign campaign to agent(s)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-campaign-user-assign">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- material datetimepicker Js -->
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script src="{{ asset('public/js/team_leader/campaign_assign.js?='.time()) }}"></script>
@append

