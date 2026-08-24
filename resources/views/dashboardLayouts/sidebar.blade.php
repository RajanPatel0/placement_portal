<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="text-decoration: none">
        <!--<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"-->
        <!--    style="opacity: .8">-->
        <span class="brand-text font-weight-light">Ptu Placement</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">




        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Profile
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-id-badge nav-icon"></i>
                                <p>My Profile</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-key nav-icon"></i>
                                <p>Forget Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" class="nav-link">
                                <i class="fas fa-user-edit nav-icon"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- Reports -->
                <!-- College -->
                @if (Auth::user()->role == 'admin')
                    <li class="nav-item">
                        @if (Auth::check())
                            <a href="{{ route('admin.college.get') }}" class="nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>College</p>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-in-alt"></i>
                                <p>Login to View Progress</p>
                            </a>
                        @endif
                    </li>

                    <!-- Placement Drives -->
                    <li class="nav-item">
                        @if (Auth::check())
                            <a href="{{ route('admin.placement.drives') }}" class="nav-link">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Placement Drives</p>
                            </a>
                        @endif
                    </li>

                    <!-- Applications -->
                    <li class="nav-item">
                        @if (Auth::check())
                            <a href="{{ route('admin.companies') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Applications</p>
                            </a>
                        @endif
                    </li>

                    <li class="nav-item">
                        @if (Auth::check())
                            <a href="{{ route('admin.announcements.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                <p>Announcements</p>
                            </a>
                        @endif
                    </li>

                    <li class="nav-item">
                        @if (Auth::check())
                            <a href="{{ route('admin.users.all') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>All Users</p>
                            </a>
                        @endif
                    </li>

               
                    <li class="nav-item">
                        <a href="{{ route('admin.placement-officers.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Placement Officers</p>
                        </a>
                    </li>


                      <li class="nav-item">
                        <a href="{{ route('adminDashboard.siteConfig') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Site Configuration</p>
                        </a>
                    </li>

                @endif

                @if (Auth::check() && Auth::user()->role == 'company')
                    <!-- Companies -->
                    <li class="nav-item">
                        <a href="{{ route('company.welcome') }}" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>



                    <li class="nav-item">
                        <a href="{{ route('company.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>View Applications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('company.shortlisted.students') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Shortlisted Students</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('company.rejected.students') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Rejected Students</p>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="{{ route('company.selected.students') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>Selected Students</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('company.all.drives') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>All placement Drives</p>
                        </a>
                    </li>

                   
                @endif

                @if (Auth::user()->role == 'placement_officer')
                    <!-- Placement Officer -->
                    <li class="nav-item">
                        <a href="{{ route('placement.officer.dashboard') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('placement.officer.students') }}" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>All Students</p>
                        </a>
                    </li>
                @endif



                <!-- Settings -->
                {{-- <li class="nav-item">
                    <a href="attendance-settings.html" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li> --}}

                {{-- <li class="nav-item">
                    <a href="{{route('otpRegister')}}" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                    Request for OTP
                </p>
                </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('otpVerify')}}" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Verify OTP
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
