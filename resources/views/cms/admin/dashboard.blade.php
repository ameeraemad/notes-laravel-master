@extends('cms.admin.parent')

<!-- Content Wrapper. Contains page content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-list"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Categories</span>
                                <span class="info-box-number">
                                    {{$categoriesCount}}
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-bars"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Notes</span>
                                <span class="info-box-number">{{$notesCount}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Students</span>
                                <span class="info-box-number">{{$studentsCount}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Users</span>
                                <span class="info-box-number">{{$usersCount}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
                <!-- /.row -->

                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <div class="col-md-12">
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">Latest Registered Students</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Status</th>
                                            <th>Users</th>
                                            <th>Create Date</th>
                                            <th>Updated Date</th>
                                            <th>Settings</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <span hidden>{{$count = 0}}</span>
                                            @foreach($students as $student)
                                                <tr>
                                                    <td><span class="badge badge-secondary">{{++$count}}</span></td>
                                                    <td>{{$student->id}}</td>
                                                    <td>{{$student->name}}</td>
                                                    <td>{{$student->email}}</td>
                                                    <td>{{$student->mobile}}</td>
                                                    <td>
                                                        @if($student->status == 'Active')
                                                            <span class="badge badge-success">{{$student->status}}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{$student->status}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm"
                                                        href="{{route('cms.admin.student.users',[$student->id])}}">
                                                            <i class="fas fa-user">
                                                            </i>
                                                            ({{$student->users_count}}) User/s
                                                        </a>
                                                    </td>
                                                    <td>{{$student->created_at->format('d/m/Y')}}</td>
                                                    <td>{{$student->updated_at->format('d/m/Y')}}</td>
                                                    <td>
                                                        @can('update-student')
                                                            <a class="btn btn-info btn-sm"
                                                            href="{{route('students.edit',[$student->id])}}">
                                                                <i class="fas fa-pencil-alt">
                                                                </i>
                                                                Edit
                                                            </a>
                                                        @endcan
                                                        @can('delete-student')
                                                            <a class="btn btn-danger btn-sm" href="#"
                                                            onclick="confirmDelete(this, '{{$student->id}}')">
                                                                <i class="fas fa-trash">
                                                                </i>
                                                                Delete
                                                            </a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{--                <a href="{{route('notifications.create')}}" class="btn btn-sm btn-info float-left">Send New Notification</a>--}}
                                {{--                <a href="{{route('notifications.index')}}" class="btn btn-sm btn-secondary float-right">View All Sent Notificaions</a>--}}
                            </div>
                            <!-- /.card-footer -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
<!-- /.content-wrapper -->

@section('scripts')
    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="{{asset('cms/plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
    <script src="{{asset('cms/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('cms/plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
    <script src="{{asset('cms/plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('cms/plugins/chart.js/Chart.min.js')}}"></script>

    <!-- PAGE SCRIPTS -->
    <script src="{{asset('cms/dist/js/pages/dashboard2.js')}}"></script>

@endsection
