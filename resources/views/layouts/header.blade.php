<style>
    /* Custom CSS for hover dropdown */
    .navbar-nav .dropdown:hover .dropdown-menu {
        display: block;
    }

    .navbar {
        color: white;
    }

    .nav-link.active {
        background-color: #fff;
        color: #76230ac4 !important;
    }
</style>

<nav class="main-header navbar navbar-expand-md navbar navbar-s" style="background-color: #76230ac4; color:white; font-weight:bold;">

    <div class="container ">
        <a href="#" class="navbar-brand">
            <img src="{{ asset('assets/dist/img/logo.png') }}" alt="Sanghavi Jewellers Logo"
                class="brand-image img-circle elevation-6" style="height: 45px;">
            <span class="brand-text font-weight text-white">SANGHAVI JEWELLERS</span>
        </a>
    </div>
    <div>

        <div class="collapse navbar-collapse order-3 align-content-md-center" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link text-white {{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>

                <li class="nav-item dropdown">
                    <a id="Transitions" href="{{ url('transactions') }}" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle text-white {{ Request::is('transactions') ? 'active' : '' }}">Transactions</a>
                    <ul aria-labelledby="dropdownSubMenu" class="dropdown-menu border-0 shadow">
                        <li><a href="{{ url('transactions') }}" class="dropdown-item {{ Request::is('transactions') ? 'active' : '' }}">Transactions</a></li>
                        <li><a href="{{ url('add-transaction') }}" class="dropdown-item {{ Request::is('add-transaction') ? 'active' : '' }}">Add Transactions</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ url('customer') }}" class="nav-link text-white {{ Request::is('customer') ? 'active' : '' }}">Customers</a>
                </li>
                <li class="nav-item dropdown">
                    <a id="Transitions" href="{{ url('call_reminder') }}" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle text-white {{ Request::is('call_reminder') ? 'active' : '' }}">Communication</a>
                    <ul aria-labelledby="dropdownSubMenu" class="dropdown-menu border-0 shadow">
                        <li><a href="{{ url('call_reminder') }}" class="dropdown-item {{ Request::is('call_reminder') ? 'active' : '' }}">Call Remainder</a></li>
                        <li><a href="{{ url('reminders') }}" class="dropdown-item {{ Request::is('reminders') ? 'active' : '' }}">SMS/Whatsapp</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ url('staff') }}" class="nav-link text-white {{ Request::is('staff') ? 'active' : '' }}">Staff</a>
                </li>
                <li class="nav-item dropdown">
                    <a id="Transitions" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        class="nav-link dropdown-toggle text-white {{ Request::is('reports') ? 'active' : '' }}">Reports</a>
                    <ul aria-labelledby="dropdownSubMenu" class="dropdown-menu border-0 shadow">
                        <li><a href="{{ url('message-logs') }}" class="dropdown-item {{ Request::is('message-logs') ? 'active' : '' }}">Message Logs</a></li>
                        <li><a href="{{ url('paylogs') }}" class="dropdown-item {{ Request::is('paylogs') ? 'active' : '' }}">Payment Logs</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" class="nav-link dropdown-toggle text-white {{ Request::is('miscellaneous') ? 'active' : '' }}">Miscellaneous</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li><a href="{{ url('grades') }}" class="dropdown-item {{ Request::is('grades') ? 'active' : '' }}">Grades</a></li>
                        <li><a href="{{ url('groups') }}" class="dropdown-item {{ Request::is('groups') ? 'active' : '' }}">Groups</a></li>
                        <li><a href="{{ url('sms-templates') }}" class="dropdown-item {{ Request::is('sms-templates') ? 'active' : '' }}">Sms Templates</a></li>
                        <li><a href="{{ url('whatsapp-templates') }}" class="dropdown-item {{ Request::is('whatsapp-templates') ? 'active' : '' }}">Whatsapp Templates</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <a href="{{ url('customer') }}" class="nav-link text-white {{ Request::is('customer') ? 'active' : '' }}"><i class="fa fa-user me-1"></i></a>
                </li>
                <li class="nav-item" >
                    <a href="{{ url('/') }}" class="nav-link text-white"><i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>

