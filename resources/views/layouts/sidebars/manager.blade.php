<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'manager.dashboard') active @endif">
        <a href="{{ route('manager.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('manager.campaign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">Campaign Management</span>
        </a>
    </li>

    <li class="nav-item pcoded-hasmenu @if(Request::route()->getName() == '') active pcoded-trigger @endif">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Campaign Management</span></a>
        <ul class="pcoded-submenu">
            <li class="@if(Request::route()->getName() == '') active @endif"><a href="javascript:void(0);" class="">Campaign List</a></li>
            <li class="@if(Request::route()->getName() == '') active @endif"><a href="javascript:void(0);" class="">Campaign Assign</a></li>
        </ul>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('manager.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-align-justify"></i></span>
            <span class="pcoded-mtext">History</span>
        </a>
    </li>

</ul>

