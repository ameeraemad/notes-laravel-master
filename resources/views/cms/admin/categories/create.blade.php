@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Category - Create</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Create Category</li>
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
                                <h3 class="card-title">Create Category</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" id="create_category_form" method="post">
                                @csrf

                                <div class="card-body">
                                    <div class="alert alert-danger" id="error_alert" role="alert" hidden>
                                        <ul id="error_messages_ul"></ul>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>User</label>
                                            <select id="user_id" class="form-control select2bs4" style="width: 100%;">
                                                <option value="" selected disabled hidden>Select User</option>
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}">{{$user->email}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Category Name</label>
                                            <input id="title" value="{{old('title')}}" type="text" class="form-control"
                                                   placeholder="Category Title">
                                        </div>
                                        <div class="form-group">
                                            <label for="details">Category Details</label>
                                            <input id="details" value="{{old('details')}}" type="text" class="form-control"
                                                   placeholder="Category Details">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="button" onclick="createCategory()" class="btn btn-primary">Save</button>
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
        function createCategory(){
            axios.post('/cms/admin/categories', {
                user_id: document.getElementById("user_id").value,
                title: document.getElementById("title").value,
                details: document.getElementById("details").value,
            })
            .then(function (response) {
                clearAndHideErrors();
                clearForm();
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

        function clearForm(){
            document.getElementById("create_category_form").reset();
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
