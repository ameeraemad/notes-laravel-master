@extends('cms.admin.parent')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notification - Send</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Update Notification</li>
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
                                <h3 class="card-title">Update Notification</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" id="send_single_notification_form" method="post">
                                @csrf
                                @method('PUT')

                                <div class="card-body">
                                    <div class="alert alert-danger" id="error_alert" role="alert" hidden>
                                        <ul id="error_messages_ul"></ul>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input id="title" value="{{$notification->title}}" type="text" class="form-control"
                                                   placeholder="Notification Title">
                                        </div>
                                        <div class="form-group">
                                            <label for="sub_title">Sub-Title</label>
                                            <input id="sub_title" value="{{$notification->sub_title}}" type="text"
                                                   class="form-control"
                                                   placeholder="Notification Sub-Title">
                                        </div>
                                        <div class="form-group">
                                            <label>Body</label>
                                            <textarea class="form-control" id="body" rows="5"
                                                      placeholder="Notification Body">{{$notification->body}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="button" onclick="updateNotification('{{$notification->id}}')" class="btn btn-primary">
                                        Save
                                    </button>
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
        function updateNotification(id) {
            axios.put('/cms/admin/notifications/' + id, {
                title: document.getElementById("title").value,
                sub_title: document.getElementById("sub_title").value,
                body: document.getElementById("body").value,
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

        function clearAndHideErrors() {
            document.getElementById('error_alert').hidden = true
            var errorMessagesUl = document.getElementById("error_messages_ul");
            errorMessagesUl.innerHTML = '';
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
