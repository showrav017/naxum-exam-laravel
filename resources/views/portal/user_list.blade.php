@include('portal.inc.header')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div><!-- /.col -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-new-user">Add New User</button>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover table-responsive-sm">
                        <thead>
                        <tr>
                            <th>User Name</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact Number</th>
                            <th>E-Mail</th>
                            <th>Last Logged</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-add-new-user">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">First Name</label>
                        <div class="col-sm-8">
                            <input v-model="settings.first_name" type="text" class="form-control" placeholder="Put First Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Last Name</label>
                        <div class="col-sm-8">
                            <input v-model="settings.last_name" type="text" class="form-control" placeholder="Put Last Name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Contact Number</label>
                        <div class="col-sm-8">
                            <input v-model="settings.mobile_number" type="text" class="form-control" placeholder="Put Contact Number">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">E-Mail</label>
                        <div class="col-sm-8">
                            <input v-model="settings.email" type="text" class="form-control" placeholder="Put E-Mail">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">User Name (For Panel Login)</label>
                        <div class="col-sm-8">
                            <input v-model="settings.user_name" type="text" class="form-control" placeholder="Put User Name (For Panel Login)">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Password (For Panel Login)</label>
                        <div class="col-sm-8">
                            <input v-model="settings.user_password" type="text" class="form-control" placeholder="Put Password (For Panel Login)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" v-on:click="createUser()">Create</button>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="{{ env("APP_URL") }}assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ env("APP_URL") }}assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ env("APP_URL") }}assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ env("APP_URL") }}assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ env("APP_URL") }}assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ env("APP_URL") }}assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
    var masterTable;
    window.addEventListener('load', function() {
        masterTable = $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "columnDefs": [
                {
                    'targets': 6,'searchable': false, 'orderable': false,
                    'render': function (data, type, full, meta)
                    {
                        var info = $('<div/>').text(data).html();

                        return (info.length> 0?'<button onclick="removeUser('+"'"+info+"'"+')" type="button" class="btn btn-danger btn-xs">Remove</button>':'');
                    }
                },
            ],
            "ajax": {
                "url": "{{ env("APP_URL") }}api/users/list",
                "type": "POST",
                'beforeSend': function (request) {
                    request.setRequestHeader("Authorization", 'Bearer <?php echo session('AuthorizationToken'); ?>');
                },
                "data": function ( d )
                {
                    //d = theInstance.tableFilter
                },
                complete: function(data)
                {
                    //theInstance.dTableMount();
                },
                error: function (xhr, error, thrown)
                {
                    console.log(xhr);
                    console.log(error);
                    console.log(thrown);
                    if(xhr.status == 401)
                    {
                        alert("You have to Login Again")
                        window.location.href = "{{ env("APP_URL") }}logout";
                    }
                }
            },
        });
    });

    function removeUser(userId) {
        if(confirm("Are you sure?"))
        {
            axios.delete(('{{ env("APP_URL") }}api/users/remove/'+userId), {
                headers: {
                    Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                },
            })
                .then(function (response) {
                    masterTable.ajax.reload();
                })
                .catch(function (error) {
                    if (error.response) {
                        switch (error.response.status)
                        {
                            case 401:
                                alert("You have to Login Again");
                                window.location.href = "{{ env("APP_URL") }}logout";
                                break;
                        }
                    }
                });
        }
    }


    const { createApp } = Vue
    var theInstance;
    createApp({
        data() {
            return {
                settings:{
                    "first_name":"",
                    "mobile_number":"",
                    "last_name":"",
                    "user_name":"",
                    "user_password":"",
                    "email":"",
                }
            }
        },
        mounted()
        {
            theInstance = this;
        },
        methods: {
            createUser()
            {
                axios.post('{{ env("APP_URL") }}api/users/create', theInstance.settings, {
                    headers: {
                        Authorization: 'Bearer <?php echo session('AuthorizationToken'); ?>'
                    },
                })
                    .then(function (response) {
                        $('#modal-add-new-user').modal('hide');
                        alert("User have been created Successfully.")
                        masterTable.ajax.reload();
                    })
                    .catch(error => {
                        if (error.response) {
                            switch (error.response.status)
                            {
                                case 401:
                                    alert("You have to Login Again");
                                    window.location.href = "{{ env("APP_URL") }}logout";
                                    break;
                                default:
                                    alert(JSON.stringify(error.response.data.message))
                            }
                        }
                    });
            }
        }
    }).mount('#app')
</script>
<style>
    .pagination{
        float: right;
    }
</style>

@include('portal.inc.footer')
