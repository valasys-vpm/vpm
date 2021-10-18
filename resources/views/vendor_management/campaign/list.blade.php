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
                                        <h5 class="m-b-10">Campaign Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href=""><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="">Campaign Management</a></li>
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
                                <!-- [ configuration table ] start -->
                                <div class="col-sm-12">

                                    <div class="card">
                                        <div class="card-header">

                                            <h5><i class="fas fa-list m-r-5"></i> Campaign Assign</h5>
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
                                            <form id="form-campaign-user-assign">

                                                <input type="hidden" id="action-campaign-user-assign" name="action" value="">
                                                <div class="row">
                                                    <div class="col-md-6 form-group">
                                                        <label for="campaign_status">Select Campaign(s)</label>
                                                        <select class="js-example-basic-multiple col-sm-12" multiple="multiple" id="campaign_list" name="campaign_list[]" style="height: unset;" >
                                                            <option value="">camp 1</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 form-group">
                                                        <label for="campaign_status">Select Vendor(s)</label>
                                                        <select class="js-example-basic-multiple col-sm-12" multiple="multiple" id="vendor_list" name="vendor_list[]" style="height: unset;" multiple>
                                                            @foreach ($resultVendors as $vendor)
                                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right">
                                                        <button id="button-filter-reset" type="reset" class="btn btn-outline-dark btn-square btn-sm"><i class="fas fa-undo m-r-5"></i>Reset</button>
                                                        <button id="button-campaign-user-assign" type="button" class="btn btn-outline-primary btn-square btn-sm"><i class="fas fa-filter m-r-5"></i>Apply</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign list</h5>
                                            <div class="card-header-right">

                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item"><a href="#!" id="reload-campaign-list"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block" style="padding-top: 10px;">
                                            <div class="row float-right" style="padding-bottom: 5px;">
                                                <div class="col-md-12">

                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="table-campaigns" class="display table nowrap table-striped table-hover text-center" style="width: 100%;">
                                                    <thead class="text-center">
                                                    <tr>
                                                        <th>Campaign ID</th>
                                                        <th>Name</th>
                                                        <th>Vendor(s)</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Allocation</th>
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

    <div id="campaign-user-assign-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form id="form-campaign-user-assignment" method="post" action="">
                    @csrf
                    <input type="hidden" id="action-campaign-bulk-import" name="action" value="">

                    <div class="modal-header">
                        <h5 class="modal-title">Assign campaign to user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="">
                        <div class="modal-body">
                            <div id="div-campaign-user-assigned-preview">

                            </div>
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
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <!-- toolbar Js -->
    <script src="{{ asset('public/template/assets/plugins/toolbar/js/jquery.toolbar.min.js') }}"></script>
    <script src="{{ asset('public/js/vendor_management/campaign.js?='.time()) }}"></script>

    <script>
        $(function () {             //document.cookie = "username="+window.location.href+"";
            // let cookies = document.cookie;
            // alert(cookies);
            //     $.getJSON("https://api.ipify.org?format=json", function (data) {
            //
            //         alert("IP: " + data.ip );
            //     })
            // });
        })
    </script>

@append

