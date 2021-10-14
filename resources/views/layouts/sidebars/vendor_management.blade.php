<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'vendor_management.dashboard') active @endif">
        <a href="{{ route('vendor_management.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Dashboard</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('vendor_management.vendor.list'))) active @endif">
        <a href="{{ route('vendor_management.vendor.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">Vendor Management</span>
        </a>
    </li>

    <li class="nav-item @if(in_array(Request::route()->getName(), array('vendor_management.campaign.list'))) active @endif">
        <a href="{{ route('vendor_management.campaign.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">Campaign Assign</span>
        </a>
    </li>
    
</ul>

