@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- footable css -->
    <link rel="stylesheet" href="{{ asset('public/template/') }}/assets/plugins/footable/css/footable.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('public/template/') }}/assets/plugins/footable/css/footable.standalone.min.css">

    <!-- select2 css -->
    <link rel="stylesheet" href="{{ asset('public/template/assets/plugins/select2/css/select2.min.css') }}">

    <!-- material datetimepicker css -->
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/plugins/material-datetimepicker/css/bootstrap-material-datetimepicker.css">

    <style>
        .modal {
            z-index: 99999999 !important;
        }
        .dtp{z-index:999999999 !important;}
    </style>
@append

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
                                        <h5 class="m-b-10">Campaign Management</h5>
                                        <div class="card-header-right mb-1" style="float: right;">
                                            {{-- <a href="{{ route('campaign') }}" class="btn btn-outline-dark btn-square btn-sm" style="font-weight: bold;"><i class="feather icon-arrow-left"></i>Back</a> --}}
                                        </div>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('agent.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('agent.campaign.list') }}">Campaign Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Campaign Details</a></li>
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
                                <div class="col-xl-4 col-md-4 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col">
                                                    <h5><span class="text-muted">ID: </span><span id="campaign_campaign_id">{{ $resultCampaign->campaign_id }}</span></h5>
                                                    <h6><span><span class="text-muted">Name: </span>{{ $resultCampaign->name }}</span></h6>
                                                    @if($resultCampaign->v_mail_campaign_id)
                                                        <h6><span><span class="text-muted">V-Mail Campaign ID: </span>{{ $resultCampaign->v_mail_campaign_id }}</span></h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign Details</h5>
                                            <div class="card-header-right">

                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <h6 class="text-muted f-w-300">Campaign Type: <span class="float-right">{{ $resultCampaign->campaignType->name }}</span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Campaign Filter: <span class="float-right">{{ $resultCampaign->campaignFilter->name }}</span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Country(s): <br><br><span class="float-right">
                                                    @foreach($resultCampaign->countries->pluck('country.name')->toArray() as $country)
                                                        <span class="badge badge-info m-1" style="padding: 5px 15px;">{{$country}}</span>
                                                    @endforeach
                                                </span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">Region(s): <br><br><span class="float-right">
                                                    @foreach($resultCampaign->countries->pluck('country.region.name')->unique()->toArray() as $region)
                                                        <span class="badge badge-dark m-1" style="padding: 5px 15px;">{{$region}}</span>
                                                    @endforeach
                                                </span></h6>
                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                            <h6 class="text-muted f-w-300 mt-4">
                                                Note: <br><br>
                                                <span class="float-right">
                                                    @if(strlen($resultCampaign->note) > 200)
                                                        <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#modal-campaign-note">View Note</button>
                                                    @else
                                                        {!! $resultCampaign->note !!}
                                                    @endif
                                                </span>
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Specifications</h5>
                                            <div class="card-header-right">

                                            </div>
                                        </div>
                                        <div class="card-block task-attachment">
                                            <ul class="media-list p-0" id="specification_ul">
                                                @php $flag = 0; @endphp
                                                @forelse($resultCampaign->specifications as $specification)
                                                    <li class="media d-flex m-b-15 specification-li">
                                                        <div class="m-r-20 file-attach">
                                                            <i class="far fa-file f-28 text-muted"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <a href="{{ url('public/storage/campaigns/'.$resultCampaign->campaign_id.'/'.$specification->file_name) }}" class="double-click" target="_blank" download data-toggle="tooltip" data-placement="top" data-original-title="{{ $specification->file_name }}"><span class="m-b-5 d-block text-primary">@if(strlen($specification->file_name) < 30) {{ $specification->file_name }} @else {{ substr($specification->file_name, 0, 27).'...' }} @endif</span></a>
                                                        </div>
                                                    </li>
                                                @empty
                                                    @php $flag = 1; @endphp
                                                @endforelse

                                                @isset($resultCampaignParent)
                                                @forelse($resultCampaignParent->specifications as $specification)
                                                    @php $flag = 0; @endphp
                                                    <li class="media d-flex m-b-15 specification-li">
                                                        <div class="m-r-20 file-attach">
                                                            <i class="far fa-file f-28 text-muted"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <a href="{{ url('public/storage/campaigns/'.$resultCampaignParent->campaign_id.'/'.$specification->file_name) }}" class="double-click" target="_blank" download data-toggle="tooltip" data-placement="top" data-original-title="{{ $specification->file_name }}"><span class="m-b-5 d-block text-primary">@if(strlen($specification->file_name) < 30) {{ $specification->file_name }} @else {{ substr($specification->file_name, 0, 27).'...' }} @endif</span></a>
                                                        </div>
                                                    </li>
                                                @empty
                                                    @php $flag = 1; @endphp
                                                @endforelse
                                                @endisset

                                                @if($flag)
                                                    <li class="media d-flex m-b-15">
                                                        <div class="media-body">
                                                            <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No File Attached</a>
                                                        </div>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5>RPF File</h5>
                                        </div>
                                        <div class="card-block task-attachment">
                                            <ul class="media-list p-0" id="rpf_file_ul">
                                                @if(!empty($resultCAAgent->reporting_file))
                                                    <li class="media d-flex m-b-15 rpf-file-li">
                                                        <div class="m-r-20 file-attach">
                                                            <i class="far fa-file f-28 text-muted"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <a href="{{ url('public/storage/campaigns/'.$resultCampaign->campaign_id.'/reporting_file/'.$resultCAAgent->reporting_file) }}" class="double-click" target="_blank" download data-toggle="tooltip" data-placement="top" data-original-title="{{ $resultCAAgent->reporting_file }}"><span class="m-b-5 d-block text-primary">@if(strlen($resultCAAgent->reporting_file) < 30) {{ $resultCAAgent->reporting_file }} @else {{ substr($resultCAAgent->reporting_file, 0, 27).'...' }} @endif</span></a>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li class="media d-flex m-b-15">
                                                        <div class="media-body">
                                                            <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No RPF File Attached</a>
                                                        </div>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-md-8">

                                    <div class="card">
                                        <div class="card-header">
                                            <h5><i class="fas fa-chart-pie m-r-5"></i> Pacing Details</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block" style="padding: 0 10px 10px 10px;">
                                            <div class="table-responsive">
                                                <table class="table m-b-0 f-14 b-solid requid-table">
                                                    <thead>
                                                    <tr class="text-uppercase">
                                                        <th class="text-center">Start Date</th>
                                                        <th class="text-center">End Date</th>
                                                        <th class="text-center">Pacing</th>
                                                        <th class="text-center">Completion</th>
                                                        <th class="text-center">Deliver Count / <br>Allocation</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="text-center text-muted">
                                                    <tr>
                                                        <td>{{ date('d-M-Y', strtotime($resultCampaign->start_date)) }}</td>
                                                        <td>{{ date('d-M-Y', strtotime($resultCAAgent->display_date)) }}</td>
                                                        <td>{{ ucfirst($resultCampaign->pacing) }}</td>
                                                        <td>
                                                            @php
                                                                $percentage = ($resultCampaign->deliver_count/$resultCampaign->allocation)*100;
                                                                $percentage = number_format($percentage,2,".", "");
                                                                if($percentage == 100) {
                                                                    $color_class = 'bg-success';
                                                                } else {
                                                                    $color_class = 'bg-warning text-dark';
                                                                }
                                                            @endphp
                                                            <div class="progress mb-4" style="height: 20px;border: 1px solid #e2dada;">
                                                                <div class="progress-bar {{ $color_class }}" role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentage}}%; font-weight: bolder;">{{$percentage}}%</div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($resultCampaign->campaign_status_id === 6)
                                                                {{ $resultCampaign->deliver_count }} <span class="text-danger" title="Shortfall Count">({{ $resultCampaign->shortfall_count }})</span> / {{ $resultCampaign->allocation }}
                                                            @else
                                                                {{ $resultCampaign->deliver_count.' / '.$resultCampaign->allocation }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $campaign_type = '';
                                                                if($resultCampaign->type == 'incremental') {
                                                                    $campaign_type = ' (Incremental)';
                                                                }
                                                            @endphp
                                                            @switch($resultCampaign->campaign_status_id)
                                                                @case(1)
                                                                <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">Live{{ $campaign_type }}</span>
                                                                @break
                                                                @case(2)
                                                                <span class="badge badge-pill badge-warning" style="padding: 5px;min-width: 70px;">Paused{{ $campaign_type }}</span>
                                                                @break
                                                                @case(3)
                                                                <span class="badge badge-pill badge-danger" style="padding: 5px;min-width: 70px;">Cancelled{{ $campaign_type }}</span>
                                                                @break
                                                                @case(4)
                                                                <span class="badge badge-pill badge-primary" style="padding: 5px;min-width: 70px;">Delivered{{ $campaign_type }}</span>
                                                                @break
                                                                @case(5)
                                                                <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">Reactivated{{ $campaign_type }}</span>
                                                                @break
                                                                @case(6)
                                                                <span class="badge badge-pill badge-secondary" style="padding: 5px;min-width: 80px;">Shortfall{{ $campaign_type }}</span>
                                                                @break
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div id="div-start-campaign" class="col-md-3" @if(!empty($resultCAAgent->started_at)) style="display: none;" @endif>
                                            <button type="button" class="btn btn-success btn-sm btn-square w-100" onclick="startCampaign('{{ base64_encode($resultCAAgent->id) }}');">Start Campaign</button>
                                        </div>
                                        <div id="div-manage-leads" class="col-md-3" @if(empty($resultCAAgent->started_at)) style="display: none;" @endif>
                                            <a href="{{ route('agent.lead.list', base64_encode($resultCAAgent->id)) }}">
                                                <button type="button" class="btn btn-primary btn-sm btn-square w-100">Manage Leads</button>
                                            </a>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-info btn-sm btn-square w-100">Get Data</button>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-danger btn-sm btn-square w-100">Submit Campaign</button>
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

    <div id="modal-campaign-note" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="background: rgba(0, 0, 0, 0.7) !important;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Campaign Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12" style="font-size: 17px;">
                        {!! $resultCampaign->note !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
<!-- footable Js -->
    <script src="{{ asset('public/template/assets/plugins/footable/js/footable.min.js') }}"></script>
    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- material datetimepicker Js -->
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- Ckeditor js -->
    <script src="{{ asset('public/template/assets/plugins/ckeditor/js/ckeditor.js') }}"></script>
    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <!-- Page Custom Js -->
    <script src="{{ asset('public/js/agent/campaign_show.js?='.time()) }}"></script>
@append
