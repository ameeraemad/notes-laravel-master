<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Notes System | CMS</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('cms/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('cms/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('cms/dist/css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    @yield('head')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{route('cms.admin.dashboard')}}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{asset('images/notes_system.png')}}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Notes System</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset('images/notes_system.png')}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{Auth::user()->name}}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                        <li class="nav-item has-treeview menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('cms.admin.dashboard')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if(auth('admin')->check())
                        @if(auth()->user()->can('create-admin') || auth()->user()->can('read-admins'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-star"></i>
                                <p>
                                    Admins
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-admins')
                                <li class="nav-item">
                                    <a href="{{route('admins.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-admin')
                                <li class="nav-item">
                                    <a href="{{route('admins.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @endif

                        @if(auth()->user()->can('create-category') || auth()->user()->can('read-categories') ||
                        auth()->user()->can('create-note') || auth()->user()->can('read-notes'))
                        <li class="nav-header">Content</li>
                        @if(auth()->user()->can('create-category') || auth()->user()->can('read-categories'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Categories
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-categories')
                                <li class="nav-item">
                                    <a href="{{route('categories.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-category')
                                <li class="nav-item">
                                    <a href="{{route('categories.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can('create-note') || auth()->user()->can('read-notes'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-pen"></i>
                                <p>
                                    Notes
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-notes')
                                <li class="nav-item">
                                    <a href="{{route('notes.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-note')
                                <li class="nav-item">
                                    <a href="{{route('notes.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @endif

                        @if(auth('admin')->check())
                        @if(auth()->user()->can('create-student') || auth()->user()->can('read-students'))
                        <li class="nav-header">Students</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Students
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-students')
                                <li class="nav-item">
                                    <a href="{{route('students.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-student')
                                <li class="nav-item">
                                    <a href="{{route('students.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @endif

                        @if(auth()->user()->can('create-user') || auth()->user()->can('read-users'))
                        <li class="nav-header">Users</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-users')
                                <li class="nav-item">
                                    <a href="{{route('users.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-user')
                                <li class="nav-item">
                                    <a href="{{route('users.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can('create-notification') || auth()->user()->can('read-notifications'))
                        <li class="nav-header">Notifications Management</li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Notifications
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-notifications')
                                <li class="nav-item">
                                    <a href="{{route('notifications.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-notification')
                                <li class="nav-item">
                                    <a href="{{route('notifications.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth('admin')->check())
                        @if(auth()->user()->can('create-role') || auth()->user()->can('read-roles') ||
                        auth()->user()->can('create-permission') || auth()->user()->can('read-permission'))
                        <li class="nav-header">Roles & Permission</li>
                        @if(auth()->user()->can('create-role') || auth()->user()->can('read-roles'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-signature"></i>
                                <p>
                                    Roles
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-roles')
                                <li class="nav-item">
                                    <a href="{{route('roles.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-role')
                                <li class="nav-item">
                                    <a href="{{route('roles.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                        @if(auth()->user()->can('create-permission') || auth()->user()->can('read-permission'))
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-sign"></i>
                                <p>
                                    Permissions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('read-permissions')
                                <li class="nav-item">
                                    <a href="{{route('permissions.index')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Index</p>
                                    </a>
                                </li>
                                @endcan
                                @can('create-permission')
                                <li class="nav-item">
                                    <a href="{{route('permissions.create')}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
                        @endif
                        @endif

                        <li class="nav-header">Settings & Contact</li>
                        <li class="nav-item">
                            <a @if(auth('admin')->check())
                                href="{{route('admins.edit',[Auth::user()->id])}}"
                                @else
                                href="{{route('students.edit',[Auth::user()->id])}}"
                                @endif
                                class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Edit Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a @if(auth('admin')->check())
                                href="{{route('cms.admin.password_reset_view')}}"
                                @else
                                href="{{route('cms.student.password_reset_view')}}"
                                @endif
                                class="nav-link">
                                <i class="nav-icon fas fa-lock"></i>
                                <p>Reset Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a @if(auth('admin')->check())
                                href="{{route('cms.admin.logout')}}"
                                @else
                                href="{{route('cms.student.logout')}}"
                                @endif
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <@yield('content') <!-- /.content-wrapper -->

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <strong>Copyright &copy; {{\Carbon\Carbon::now()->year}} <a href="#">Momen Sisalem
                        - {{env('APP_NAME')}}</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Version</b> {{env('APP_VERSION')}}
                </div>
            </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{asset('cms/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('cms/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('cms/dist/js/adminlte.js')}}"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="{{asset('cms/dist/js/demo.js')}}"></script>

    @yield('scripts')
</body>

</html>