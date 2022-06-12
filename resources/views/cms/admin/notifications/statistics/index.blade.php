@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notification Statistics</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Notification Statistics</li>
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
                            <h3 class="card-title">Notes System - Notification Statistics</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Send Status</th>
                                    <th>Seen Status</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{$count = 0}}
                                @foreach($notificationStatistics as $notificationStatistic)
                                    <tr>
                                        <td><span class="badge badge-secondary">{{++$count}}</span></td>
                                        <td>{{$notificationStatistic->id}}</td>
                                        <td>
                                            <span class="badge badge-info">
                                            {{$notificationStatistic->user->first_name.' '.$notificationStatistic->user->last_name}}
                                            </span>
                                        </td>
                                        <td>
                                            @if($notificationStatistic->send_status == "Success")
                                                <span
                                                    class="badge badge-success">{{$notificationStatistic->send_status}}</span>
                                            @elseif($notificationStatistic->status == "Failed")
                                                <span
                                                    class="badge badge-danger">{{$notificationStatistic->send_status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($notificationStatistic->seen_status == "Seen")
                                                <span
                                                    class="badge badge-success">{{$notificationStatistic->seen_status}}</span>
                                            @elseif($notificationStatistic->seen_status == "NotSeen")
                                                <span
                                                    class="badge badge-danger">{{$notificationStatistic->seen_status}}</span>
                                            @endif
                                        </td>
                                        <td>{{$notificationStatistic->created_at->format('d/m/Y - h:m')}}</td>
                                        <td>{{$notificationStatistic->updated_at->format('d/m/Y - h:m')}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>User Name</th>
                                    <th>Send Status</th>
                                    <th>Seen Status</th>
                                    <th>Create Date</th>
                                    <th>Updated Date</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row justify-content-center">
                            {{$notificationStatistics->render()}}
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
@endsection
