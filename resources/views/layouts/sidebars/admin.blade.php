<ul class="nav pcoded-inner-navbar">


    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-layers"></i></span>
            <span class="pcoded-mtext">Campaign Types</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">feather icon-filter</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
            <span class="pcoded-mtext">User Management</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.role.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-server"></i></span>
            <span class="pcoded-mtext">Role Management</span>
        </a>
    </li>

    <li class="nav-item pcoded-hasmenu">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-map"></i></span><span class="pcoded-mtext">Geo Management</span></a>
        <ul class="pcoded-submenu">
            <li class=""><a href="javascript:void(0);" class="">Countries</a></li>
            <li class=""><a href="{{ route('admin.geo.region.list') }}" class="">Regions</a></li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-calendar"></i></span>
            <span class="pcoded-mtext">Holidays</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-settings"></i></span>
            <span class="pcoded-mtext">Site Settings</span>
        </a>
    </li>

    <li class="nav-item pcoded-menu-caption">
        <label>Users Menu</label>
    </li>

    <li class="nav-item pcoded-hasmenu">
        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-list"></i></span><span class="pcoded-mtext">Campaign Management</span></a>
        <ul class="pcoded-submenu">
            <li class=""><a href="javascript:void(0);" class="">Campaign List</a></li>
            <li class=""><a href="javascript:void(0);" class="">Campaign Assign</a></li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link" target="_blank">
            <span class="pcoded-micon"><i class="feather icon-align-justify"></i></span>
            <span class="pcoded-mtext">History</span>
        </a>
    </li>

</ul>

