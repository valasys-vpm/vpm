<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'manager.dashboard') active @endif">
        <a href="{{ route('manager.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('manager.campaign.list', 'manager.campaign.show'))) active @endif">
        <a href="{{ route('manager.campaign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">Campaign Management</span>
        </a>
    </li>

    <li class="nav-item @if(Request::route()->getName() == '') active @endif">
        <a href="{{ route('manager.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-align-justify"></i></span>
            <span class="pcoded-mtext">History</span>
        </a>
    </li>

</ul>

