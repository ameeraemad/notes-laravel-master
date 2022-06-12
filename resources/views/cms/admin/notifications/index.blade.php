@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notifications</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
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
                            <h3 class="card-title">Notes System - Notifications</h3>
                            @can('create-notification')
                                <a href="{{route('notifications.create')}}" class="btn btn-sm btn-info float-right">Send
                                    New
                                    Notification</a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Sub-Title</th>
                                    <th>Body</th>
                                    <th>Sender</th>
                                    <th>Receivers</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                    <th>Settings</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$count = 0}}
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td><span class="badge badge-secondary">{{++$count}}</span></td>
                                        <td>{{$notification->id}}</td>
                                        <td><span class="badge badge-info">{{$notification->title}}</span></td>
                                        <td>{{$notification->sub_title}}</td>
                                        <td>{{$notification->body}}</td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{$notification->sender->name}}
                                            </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-sm"
                                               href="{{route('notifications.show',[$notification->id])}}">
                                                <i class="fas fa-sticky-note">
                                                </i>
                                                ({{$notification->notification_statistics_count}}) Receiver/s
                                            </a>
                                            <span class="badge badge-dark"></span>
                                        </td>
                                        <td>{{$notification->created_at->format('d/m/Y - h:m')}}</td>
                                        <td>{{$notification->updated_at->format('d/m/Y - h:m')}}</td>
                                        <td>
                                            @can('update-notification')
                                                <a class="btn btn-info btn-sm"
                                                   href="{{route('notifications.edit',[$notification->id])}}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>
                                                    Edit
                                                </a>
                                            @endcan
                                            @can('delete-notification')
                                                <a class="btn btn-danger btn-sm" href="#"
                                                   onclick="confirmDelete(this, '{{$notification->id}}')">
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
                                    <th>Title</th>
                                    <th>Sub-Title</th>
                                    <th>Body</th>
                                    <th>Sender</th>
                                    <th>Receivers</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                    <th>Settings</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            {{$notifications->render()}}
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
                    deleteNotification(app, id)
                }
            })
        }

        function deleteNotification(app, id) {
            axios.delete('/cms/admin/notifications/' + id)
                .then(function (response) {
                    // handle success (Status Code: 200)
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
