@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Students</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Students</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Notes System - Students</h3>
                            <a href="{{route('students.create')}}" class="btn btn-sm btn-info float-right">Create New
                                Student</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>API-UUID</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                    <th>Settings</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$count = 0}}
                                @foreach($students as $student)
                                    <tr>
                                        <td><span class="badge badge-secondary">{{++$count}}</span></td>
                                        <td>{{$student->id}}</td>
                                        <td>{{$student->name}}</td>
                                        <td>{{$student->email}}</td>
                                        <td>{{$student->mobile}}</td>
                                        <td>
                                            @if($student->gender == 'Male')
                                                <span class="badge badge-info">{{$student->gender}}</span>
                                            @else
                                                <span class="badge badge-warning">{{$student->gender}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($student->status == 'Active')
                                                <span class="badge badge-success">{{$student->status}}</span>
                                            @else
                                                <span class="badge badge-danger">{{$student->status}}</span>
                                            @endif
                                        </td>
                                        <td><span class="badge badge-primary">{{$student->api_uuid}}</span></td>
                                        <td>
                                            <a class="btn btn-info btn-sm" href="#">
                                                <i class="fas fa-signature">
                                                </i>
                                                {{count($student->getRoleNames())}} Role\s
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                               href="{{route('students.edit-permissions',[$student->id])}}">
                                                <i class="fas fa-sign">
                                                </i>
                                                Permission/s
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                               href="{{route('cms.admin.student.users',[$student->id])}}">
                                                <i class="fas fa-user">
                                                </i>
                                                ({{$student->users_count}}) User/s
                                            </a>
                                        </td>
                                        <td>{{$student->created_at->format('d/m/Y - h:m')}}</td>
                                        <td>{{$student->updated_at->format('d/m/Y - h:m')}}</td>
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
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Gender</th>
                                    <th>Status</th>
                                    <th>API-UUID</th>
                                    <th>Roles</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                    <th>Settings</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            {{$students->render()}}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('cms/plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('cms/plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        function confirmDelete(app, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    deleteAdmin(app, id)
                }
            })
        }

        function deleteAdmin(app, id) {
            // alert("Delete admin Function: "+id);

            axios.delete('/cms/admin/students/' + id)
                .then(function (response) {
                    // handle success (Status Code: 200)
                    console.log(response);
                    console.log(response.data);
                    showMessage(response.data);
                    app.closest('tr').remove();
                })
                .catch(function (error) {
                    // handle error (Status Code: 400)
                    console.log(error);
                    console.log(error.response.data);
                    showMessage(error.response.data);
                })
                .then(function () {
                    // always executed
                });
        }

        function showMessage(data) {
            Swal.fire({
                position: 'center',
                icon: data.icon,
                title: data.title,
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
@endsection
