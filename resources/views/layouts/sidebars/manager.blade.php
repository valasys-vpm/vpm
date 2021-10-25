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

    <li class="nav-item @if(in_array(Request::route()->getName(), array('manager.campaign_assign.list'))) active @endif">
        <a href="{{ route('manager.campaign_assign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
            <span class="pcoded-mtext">Campaign Assign</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('manager.data.list'))) active @endif">
        <a href="{{ route('manager.data.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-layers"></i></span>
            <span class="pcoded-mtext">Data Management</span>
        </a>
    </li>

</ul>

