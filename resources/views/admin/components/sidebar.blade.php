<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light text-light">SOP BAZAAR</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        {{-- <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->

                @can('View Accounts')
                    <li class="nav-item">
                        <a href="{{ route('admin.accounts.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                @endcan

                @can('View Categories')
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                @endcan

                @can('View Sub Categories')
                    <li class="nav-item">
                        <a href="{{ route('admin.sub-categories.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Sub Categories</p>
                        </a>
                    </li>
                @endcan


                @can('View Status')
                    <li class="nav-item">
                        <a href="{{ route('admin.status.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Account Statuses</p>
                        </a>
                    </li>
                @endcan



                @can('View Permissions')
                    <li class="nav-item">
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                @endcan
                @can('View Roles')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan
                @can('View Users')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav> --}}



        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                @can('View Accounts')
                    <li class="nav-item">
                        <a href="{{ route('admin.accounts.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                @endcan

                @can('View Categories')
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-folder"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                @endcan

                @can('View SubCategories')
                    <li class="nav-item">
                        <a href="{{ route('admin.sub-categories.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-sitemap"></i>
                            <p>Sub Categories</p>
                        </a>
                    </li>
                @endcan



                @can('View Status')
                    <li class="nav-item">
                        <a href="{{ route('admin.status.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Account Statuses</p>
                        </a>
                    </li>
                @endcan

                @can('View Permissions')
                    <li class="nav-item">
                        <a href="{{ route('admin.permissions.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                @endcan

                @can('View Roles')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-id-badge"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan

                @can('View Users')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>




        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
