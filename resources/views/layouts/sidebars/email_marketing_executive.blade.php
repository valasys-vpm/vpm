<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'email_marketing_executive.dashboard') active @endif">
        <a href="{{ route('email_marketing_executive.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('email_marketing_executive.campaign.list', 'email_marketing_executive.campaign.show'))) active @endif">
        <a href="{{ route('email_marketing_executive.campaign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">My Campaigns</span>
        </a>
    </li>

</ul>

