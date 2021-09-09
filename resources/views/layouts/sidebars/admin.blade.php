<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'admin.dashboard') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-user"></i></span>
            <span class="pcoded-mtext">User Management</span>
        </a>
    </li>

    <li class="nav-item pcoded-hasmenu @if(Request::route()->getName() == 'admin.role.list' || Request::route()->getName() == 'admin.user_settings.department.list' || Request::route()->getName() == 'admin.user_settings.designation.list') active pcoded-trigger @endif">
        <a href="javascript:void(0);" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
            <span class="pcoded-mtext">User Settings</span>
        </a>
        <ul class="pcoded-submenu">
            <li class="@if(Request::route()->getName() == 'admin.role.list') active @endif"><a href="{{ route('admin.role.list') }}" class="">Roles</a></li>
            <li class="@if(Request::route()->getName() == 'admin.user_settings.department.list') active @endif"><a href="{{ route('admin.user_settings.department.list') }}" class="">Departments</a></li>
            <li class="@if(Request::route()->getName() == 'admin.user_settings.designation.list') active @endif"><a href="{{ route('admin.user_settings.designation.list') }}" class="">Designations</a></li>
        </ul>
    </li>

    <li class="nav-item pcoded-hasmenu @if(Request::route()->getName() == 'admin.campaign_settings.campaign_type.list' || Request::route()->getName() == 'admin.campaign_settings.campaign_filter.list') active pcoded-trigger @endif">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Campaign Settings</span></a>
        <ul class="pcoded-submenu">
            <li class="@if(Request::route()->getName() == 'admin.campaign_settings.campaign_filter.list') active @endif"><a href="{{ route('admin.campaign_settings.campaign_filter.list') }}" class="">Campaign Filters</a></li>
            <li class="@if(Request::route()->getName() == 'admin.campaign_settings.campaign_type.list') active @endif"><a href="{{ route('admin.campaign_settings.campaign_type.list') }}" class="">Campaign Types</a></li>
        </ul>
    </li>

    <li class="nav-item pcoded-hasmenu @if(Request::route()->getName() == 'admin.geo.region.list' || Request::route()->getName() == 'admin.geo.region.list') active pcoded-trigger @endif">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-map"></i></span><span class="pcoded-mtext">Geo Management</span></a>
        <ul class="pcoded-submenu">
            <li class="@if(Request::route()->getName() == 'admin.geo.country.list') active @endif"><a href="{{ route('admin.geo.country.list') }}" class="">Countries</a></li>
            <li class="@if(Request::route()->getName() == 'admin.geo.region.list') active @endif"><a href="{{ route('admin.geo.region.list') }}" class="">Regions</a></li>
        </ul>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-calendar"></i></span>
            <span class="pcoded-mtext">Holidays</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
            <span class="pcoded-mtext">Site Settings</span>
        </a>
    </li>

    <li class="nav-item pcoded-menu-caption">
        <label>Users Menu</label>
    </li>

    <li class="nav-item pcoded-hasmenu @if(Request::route()->getName() == '') active pcoded-trigger @endif">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Campaign Management</span></a>
        <ul class="pcoded-submenu">
            <li class="@if(Request::route()->getName() == '') active @endif"><a href="javascript:void(0);" class="">Campaign List</a></li>
            <li class="@if(Request::route()->getName() == '') active @endif"><a href="javascript:void(0);" class="">Campaign Assign</a></li>
        </ul>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-align-justify"></i></span>
            <span class="pcoded-mtext">History</span>
        </a>
    </li>

</ul>

