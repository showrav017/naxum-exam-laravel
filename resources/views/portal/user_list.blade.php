@include('portal.inc.header')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
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
</div>

<script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

<script>
    window.addEventListener('load', function() {
        $('#example2').DataTable({
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
                    'targets': 2,'searchable': false, 'orderable': false,
                    'render': function (data, type, full, meta)
                    {
                        var info = $('<div/>').text(data).html();

                        return '<button type="button" class="btn btn-danger btn-xs">Remove</button>'+
                            '<button type="button" class="btn btn-primary btn-xs">Change Password</button>';
                    }
                },
            ],
            "ajax": {
                "url": "/api/users/list",
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
                        window.location.href = "/logout";
                    }
                }
            },
        });
    });
</script>
<style>
    .pagination{
        float: right;
    }
</style>

@include('portal.inc.footer')