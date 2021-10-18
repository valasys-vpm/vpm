<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'team_leader.dashboard') active @endif">
        <a href="{{ route('team_leader.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('team_leader.campaign.list', 'team_leader.campaign.show'))) active @endif">
        <a href="{{ route('team_leader.campaign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">My Campaigns</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('team_leader.campaign_assign.list'))) active @endif">
        <a href="{{ route('team_leader.campaign_assign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-shuffle"></i></span>
            <span class="pcoded-mtext">Campaign Assign</span>
        </a>
    </li>

</ul>

