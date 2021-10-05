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
                                        <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('manager.campaign.list') }}">Campaign Management</a></li>
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
                                <div class="col-xl-4 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col">
                                                    <h5><span class="text-muted">ID: </span>{{ $resultCampaign->campaign_id }}</h5>
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
                                                <a href="{{ route('manager.campaign.edit', base64_encode($resultCampaign->id)) }}" class="btn btn-outline-primary btn-sm btn-square"><i class="feather icon-edit mr-0"></i> Edit</a>
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
                                                <button class="btn btn-primary btn-sm btn-square pt-1 pb-1" data-toggle="modal" data-target="#modal-attach-specification" style=""><i class="feather icon-plus mr-0"></i> Attach</button>
                                            </div>
                                        </div>
                                        <div class="card-block task-attachment">
                                            <ul class="media-list p-0" id="specification_ul">
                                                @forelse($resultCampaign->specifications as $specification)
                                                <li class="media d-flex m-b-15 specification-li">
                                                    <div class="m-r-20 file-attach">
                                                        <i class="far fa-file f-28 text-muted"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <a href="{{ url('public/storage/campaigns/'.$resultCampaign->campaign_id.'/'.$specification->file_name) }}" class="double-click" target="_blank" download data-toggle="tooltip" data-placement="top" data-original-title="{{ $specification->file_name }}"><span class="m-b-5 d-block text-primary">@if(strlen($specification->file_name) < 30) {{ $specification->file_name }} @else {{ substr($specification->file_name, 0, 27).'...' }} @endif</span></a>
                                                    </div>
                                                    <div class="float-right text-muted">
                                                        <a href="javascript:void(0);" onclick="removeSpecification(this, '{{base64_encode($specification->id)}}');"><i class="fas fa-times f-24 text-danger"></i></a>
                                                    </div>
                                                </li>
                                                @empty
                                                <li class="media d-flex m-b-15">
                                                    <div class="media-body">
                                                        <a href="javascript:void(0);" class="m-b-5 d-block text-warning">No File Attached</a>
                                                    </div>
                                                </li>
                                                @endforelse

                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8 col-lg-12">

                                    <div class="card">
                                        <div class="card-header">

                                            <h5><i class="fas fa-chart-pie m-r-5"></i> Pacing Details</h5>

                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <span>
                                                        <a href="">
                                                        <button class="btn btn-primary btn-sm btn-square pt-1 pb-1"><i class="feather icon-plus"></i>Incremental</button>
                                                        </a>
                                                    </span>
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
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table class="table m-b-0 f-14 b-solid requid-table">
                                                    <thead>
                                                    <tr class="text-uppercase">
                                                        <th class="text-center">#</th>
                                                        <th class="text-center">Start Date</th>
                                                        <th class="text-center">End Date</th>
                                                        <th class="text-center">Completion</th>
                                                        <th class="text-center">Deliver Count / <br>Allocation</th>
                                                        <th class="text-center">Status</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="text-center text-muted">
                                                    <tr>
                                                        <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>
                                                        <td>{{ date('d-M-Y', strtotime($resultCampaign->start_date)) }}</td>
                                                        <td>{{ date('d-M-Y', strtotime($resultCampaign->end_date)) }}</td>
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
                                                            @if($resultCampaign->campaign_status === 6)
                                                                {{ $resultCampaign->deliver_count }} <span class="text-danger" title="Shortfall Count">({{ $resultCampaign->shortfall_count }})</span> / {{ $resultCampaign->allocation }}
                                                            @else
                                                                {{ $resultCampaign->deliver_count.' / '.$resultCampaign->allocation }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @switch($resultCampaign->campaign_status_id)
                                                                @case(1)
                                                                <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">Live</span>
                                                                @break
                                                                @case(2)
                                                                <span class="badge badge-pill badge-warning" style="padding: 5px;min-width: 70px;">Paused</span>
                                                                @break
                                                                @case(3)
                                                                <span class="badge badge-pill badge-danger" style="padding: 5px;min-width: 70px;">Cancelled</span>
                                                                @break
                                                                @case(4)
                                                                <span class="badge badge-pill badge-primary" style="padding: 5px;min-width: 70px;">Delivered</span>
                                                                @break
                                                                @case(5)
                                                                <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">Reactivated</span>
                                                                @break
                                                                @case(6)
                                                                <span class="badge badge-pill badge-secondary" style="padding: 5px;min-width: 80px;">Shortfall</span>
                                                                @break
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                    <tr class="pacing-details" style="display: none;">
                                                        <td colspan="6" class="bg-light text-left">
                                                            <h6>
                                                                <span class="text-muted">Pacing: </span>{{ ucfirst($resultCampaign->pacing) }}
                                                            </h6>
                                                            <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                                            <table class="table table-hover foo-table text-center">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center" data-breakpoints="xs">Date</th>
                                                                    <th class="text-center" data-breakpoints="xs">Day</th>
                                                                    <th class="text-center" data-breakpoints="xs">Sub-Allocation</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($resultCampaign->pacingDetails as $subAllocation)
                                                                    @if($subAllocation->sub_allocation)
                                                                        <tr>
                                                                            <td>{{ date('d-M-Y', strtotime($subAllocation->date)) }}</td>
                                                                            <td>{{ date('D', strtotime($subAllocation->date)) }}</td>
                                                                            <td>{{ $subAllocation->sub_allocation }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @empty
                                                                @endforelse
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    {{--
                                                    @forelse($resultCampaign->leadDetails as $lead)
                                                        <tr>
                                                            <td><i class="feather icon-plus-square toggle-pacing-details" style="cursor: pointer;font-size: 17px;"></i></td>
                                                            <td>{{ date('d-M-Y', strtotime($lead->start_date)) }}</td>
                                                            @if(Auth::user()->role_id == '34')
                                                            <td>{{ date('d-M-Y', strtotime($resultCampaign->user->display_date)) }}</td>
                                                            @else
                                                            <td>{{ date('d-M-Y', strtotime($lead->end_date)) }}</td>
                                                            @endif
                                                            <td>
                                                                @php
                                                                    $percentage = ($lead->deliver_count/$lead->allocation)*100;
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
                                                                @if($lead->campaign_status == \Modules\Campaign\enum\CampaignStatus::SHORTFALL)
                                                                    {{ $lead->deliver_count }} <span class="text-danger" title="Shortfall Count">({{ $lead->shortfall_count }})</span> / {{ $lead->allocation }}
                                                                @else
                                                                    {{ $lead->deliver_count.' / '.$lead->allocation }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @switch($lead->campaign_status)
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::LIVE)
                                                                    <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::PAUSED)
                                                                    <span class="badge badge-pill badge-warning" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::CANCELLED)
                                                                    <span class="badge badge-pill badge-danger" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::DELIVERED)
                                                                    <span class="badge badge-pill badge-primary" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::REACTIVATED)
                                                                    <span class="badge badge-pill badge-success" style="padding: 5px;min-width: 70px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                    @case(\Modules\Campaign\enum\CampaignStatus::SHORTFALL)
                                                                    <span class="badge badge-pill badge-secondary" style="padding: 5px;min-width: 80px;">{{ \Modules\Campaign\enum\CampaignStatus::CAMPAIGN_STATUS[$lead->campaign_status] }}</span>
                                                                    @break
                                                                @endswitch
                                                            </td>
                                                        </tr>
                                                        <tr class="pacing-details" style="display: none;">
                                                            <td colspan="6" class="bg-light text-left">
                                                                <h6>
                                                                    <span class="text-muted">Pacing: </span>{{ $lead->pacing }}
                                                                    <span class="float-right btn btn-outline-dark btn-sm btn-square pt-1 pb-1" data-toggle="modal" onclick="editSubAllocations('{{$lead->id}}');">Update Sub-Allocations</span>
                                                                    <span class="float-right btn btn-outline-primary btn-sm btn-square pt-1 pb-1" data-toggle="modal" onclick="editLeadDetails('{{$lead->id}}','{{$lead->start_date}}','{{$lead->end_date}}','{{$lead->deliver_count}}', '{{$lead->allocation}}', '{{$lead->campaign_status}}', '{{$lead->shortfall_count}}');">Edit Lead Details</span>
                                                                </h6>
                                                                <div style="border-bottom: 1px solid #e2dada;">&nbsp;</div>
                                                                <table class="table table-hover foo-table text-center">
                                                                    <thead>
                                                                    <tr>
                                                                        <th class="text-center" data-breakpoints="xs">Date</th>
                                                                        <th class="text-center" data-breakpoints="xs">Day</th>
                                                                        <th class="text-center" data-breakpoints="xs">Sub-Allocation</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @forelse($lead->pacingDetails as $subAllocation)
                                                                        @if($subAllocation->sub_allocation)
                                                                        <tr>
                                                                            <td>{{ date('d-M-Y', strtotime($subAllocation->date)) }}</td>
                                                                            <td>{{ date('D', strtotime($subAllocation->date)) }}</td>
                                                                            <td>{{ $subAllocation->sub_allocation }}</td>
                                                                        </tr>
                                                                        @endif
                                                                    @empty
                                                                    @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                    @endforelse
                                                    --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    @if(0)
                                    <div class="card" id="card-campaign-history" style="overflow-y: auto;">
                                        <div class="card-header">
                                            <h5><i class="fas fa-clock m-r-5"></i> Campaign History</h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item reload-card"><a href="#!" id="reload-campaign-history"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <ul class="task-list" id="campaign-history-ul">

                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-center">
                                                <button id="btn-get-campaign-history" type="button" class="btn btn-warning shadow-4 btn-sm text-dark btn-square pt-1 pb-1" onclick="getCampaignHistory(this);"><i class="fas fa-spinner"></i> Load More</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
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
    @parent
    <!-- footable Js -->
    <script src="{{ asset('public/template/assets/plugins/footable/js/footable.min.js') }}"></script>

    <!-- select2 Js -->
    <script src="{{ asset('public/template/assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- material datetimepicker Js -->
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="{{ asset('public/template/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

    <!-- jquery-validation Js -->
    <script src="{{ asset('public/template/assets/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>

    <script>
        $(function(){

            $('.foo-table').footable({
                "paging": {
                    "enabled": true
                },
                /*"sorting": {
                    "enabled": true
                }*/
            });
            $('.toggle-pacing-details').click(function (){
                //$('.pacing-details').hide();
                //$('.toggle-pacing-details').removeClass('icon-minus-square').addClass('icon-plus-square');;
                if($(this).hasClass('icon-plus-square')) {
                    $(this).removeClass('icon-plus-square').addClass('icon-minus-square');
                    $(this).parents('tr').next('tr').show(1000);
                } else {
                    $(this).removeClass('icon-minus-square').addClass('icon-plus-square');
                    $(this).parents('tr').next('tr').hide(500);
                }
            });
        });
    </script>
@append
