@extends('layouts.master')

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
                                        <h5 class="m-b-10">Dashboard</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i
                                                    class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
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
                                <div class="col-md-3" style="display: none;">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Productivity</h5>
                                        </div>
                                        <div class="card-block text-center dial-chart" style="height: 270px;">
                                            <input type="text" class="dial" value="99" data-width="200" data-height="200" data-fgColor="#1de9b6" data-skin="tron" data-thickness=".1" data-angleOffset="180" data-displayInput="false" data-readonly="true">
                                            <input type="text" class="dial-value" value="99%">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Productivity</h5>
                                        </div>
                                        <div class="card-block text-center" style="height: 270px;">
                                            <div id="chart-gauge-productivity" style="width: 120%; height: 400px;left: -20px;top: -80px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Quality</h5>
                                        </div>
                                        <div class="card-block text-center" style="height: 270px;">
                                            <div id="chart-gauge-quality" style="width: 120%; height: 400px;left: -20px;top: -80px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Campaign Processed</h5>
                                        </div>
                                        <div class="card-block text-center dial-chart" style="height: 270px;">
                                            <input type="text" class="dial" value="75" data-width="200" data-height="200" data-fgColor="#66ca00" data-angleOffset="-125" data-angleArc="250" data-rotation="clockwise" data-displayInput="false" data-readonly="true">
                                            <input type="text" class="dial-value" value="299" style="color: #66ca00;">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Leads Generated</h5>
                                        </div>
                                        <div class="card-block text-center dial-chart" style="height: 270px;">
                                            <input type="text" class="dial" value="75" data-width="200" data-height="200" data-fgColor="#2a199c" data-angleOffset="-125" data-angleArc="250" data-rotation="clockwise" data-displayInput="false" data-readonly="true">
                                            <input type="text" class="dial-value" value="299" style="color: #2a199c;">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Top Productivity</h5>
                                        </div>
                                        <div class="card-block text-center" style="height: 270px;padding-top: 80px;">
                                            <div data-label="50%" class="radial-bar radial-bar-50 radial-bar-lg radial-bar-success">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                            <div data-label="40%" class="radial-bar radial-bar-40 radial-bar-sm radial-bar-warning">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                            <div data-label="30%" class="radial-bar radial-bar-30 radial-bar-xs radial-bar-danger">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Top Quality</h5>
                                        </div>
                                        <div class="card-block text-center" style="height: 270px;padding-top: 80px;">
                                            <div data-label="50%" class="radial-bar radial-bar-50 radial-bar-lg radial-bar-success">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                            <div data-label="40%" class="radial-bar radial-bar-40 radial-bar-sm radial-bar-warning">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                            <div data-label="30%" class="radial-bar radial-bar-30 radial-bar-xs radial-bar-danger">
                                                <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" alt="User-Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Leads Qualified</h5>
                                        </div>
                                        <div class="card-block text-center dial-chart" style="height: 270px;">
                                            <input type="text" class="dial" value="75" data-width="200" data-height="200" data-fgColor="#1afbcf" data-angleOffset="-125" data-angleArc="250" data-rotation="clockwise" data-displayInput="false" data-readonly="true">
                                            <input type="text" class="dial-value" value="299" style="color: #1afbcf;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Leads Rejected</h5>
                                        </div>
                                        <div class="card-block text-center dial-chart" style="height: 270px;">
                                            <input type="text" class="dial" value="75" data-width="200" data-height="200" data-fgColor="#c60a2d" data-angleOffset="-125" data-angleArc="250" data-rotation="clockwise" data-displayInput="false" data-readonly="true">
                                            <input type="text" class="dial-value" value="299" style="color: #c60a2d;">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Counts by Work Type</h5>
                                        </div>
                                        <div class="card-block text-center">
                                            <div id="bar-counts-by-work-type" style="height: 500px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Leads Generated Counts (Monthly)</h5>
                                        </div>
                                        <div class="card-block text-center">
                                            <div id="bar-leads-generated-monthly" style="height: 500px;"></div>
                                        </div>
                                    </div>
                                </div>
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
    <!-- chart-knob Js -->
    <script src="{{ asset('public/template/assets/plugins/chart-knob/js/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('public/template/assets/plugins/chart-knob/js/jquery.knob-custom.min.js') }}"></script>

    <!--echarts chart -->
    <script src="http://echarts.baidu.com/echarts2/doc/example/timelineOption.js"></script>
    <script src="{{ asset('public/template/assets/plugins/chart-echarts/js/echarts-en.min.js') }}"></script>
    <script src="{{ asset('public/js/agent/dashboard.js?='.time()) }}"></script>
@append
