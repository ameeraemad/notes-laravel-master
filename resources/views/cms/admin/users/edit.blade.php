@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin - Update</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Update Admin</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Update Admin</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" method="post" id="update_admin_form">
                                @csrf
                                <div class="card-body">

                                    <div class="alert alert-danger" id="error_alert" role="alert" hidden>
                                        <ul id="error_messages_ul"></ul>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Student</label>
                                            <select id="student" class="form-control select2bs4" style="width: 100%;">
                                                <option value="" selected disabled hidden>Select Student</option>
                                                @foreach ($students as $student)
                                                    <option value="{{$student->api_uuid}}" @if($user->student_api_uuid == $student->api_uuid) selected @endif>{{$student->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input id="first_name" value="{{$user->first_name}}" type="text"
                                                class="form-control"
                                                placeholder="Enter first name">
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input id="last_name" value="{{$user->last_name}}" type="text"
                                                   class="form-control"
                                                   placeholder="Enter last name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" value="{{$user->email}}" type="email"
                                                class="form-control" placeholder="Enter email">
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <input id="mobile" value="{{$user->mobile}}" type="tel"
                                                class="form-control" placeholder="Enter mobile">
                                        </div>
                                        <div class="form-group">
                                            <label>Account Status</label>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="account_status"
                                                    id="account_status"
                                                    @if($user->status == 'Active') checked @endif>
                                                <label class="custom-control-label" for="account_status">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                <button type="button" onclick="updateAdmin('{{$user->id}}')" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        function updateAdmin(id){
            axios.put('/cms/admin/users/'+id, {
                first_name: document.getElementById("first_name").value,
                last_name: document.getElementById("last_name").value,
                email: document.getElementById("email").value,
                mobile: document.getElementById("mobile").value,
                student: document.getElementById("student").value,
                status: document.getElementById("account_status").checked == true ? "Active" : "Blocked",
            })
            .then(function (response) {
                clearAndHideErrors();
                showMessage(response.data);
            })
            .catch(function (error) {
                if (error.response.data.errors !== undefined) {
                    showErrorMessages(error.response.data.errors);
                } else {
                    showMessage(error.response.data);
                }
            });
        }

        function showErrorMessages(errors) {
            document.getElementById('error_alert').hidden = false
            var errorMessagesUl = document.getElementById("error_messages_ul");
            errorMessagesUl.innerHTML = '';

            for (var key of Object.keys(errors)) {
                var newLI = document.createElement('li');
                newLI.appendChild(document.createTextNode(errors[key]));
                errorMessagesUl.appendChild(newLI);
            }
        }

        function clearAndHideErrors(){
            document.getElementById('error_alert').hidden = true
            var errorMessagesUl = document.getElementById("error_messages_ul");
            errorMessagesUl.innerHTML = '';
        }

        function showMessage(data){
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
